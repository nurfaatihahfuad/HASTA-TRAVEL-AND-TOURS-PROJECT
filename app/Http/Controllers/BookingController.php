<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Models\Vehicle;
use Carbon\Carbon;

class BookingController extends Controller
{
    /**
     * Show the booking form to the customer.
     */
    public function create($vehicleID, Request $request): View
    {
        $vehicle = Vehicle::findOrFail($vehicleID);

        $pickup_dateTime = $request->input('pickup_dateTime');
        $return_dateTime = $request->input('return_dateTime');

        // Calculate total hours and total price
        $pickup = Carbon::parse($pickup_dateTime);
        $return = Carbon::parse($return_dateTime);
        
        $totalHours = round($pickup->diffInHours($return));
        $totalHours = $totalHours == 0 ? 1 : $totalHours; // Minimum 1 hour
        
        $pricePerHour = $vehicle->price_per_hour; // Convert daily rate to hourly
        $totalPrice = $pricePerHour * $totalHours;
        
        // Pass additional data to view
        return view('booking', compact(
            'vehicle', 
            'pickup_dateTime', 
            'return_dateTime',
            'totalHours',
            'totalPrice',
            'pricePerHour'
        ));

        return view('booking', compact('vehicle', 'pickup_dateTime', 'return_dateTime'));
    }

    /**
     * Store the booking submitted by the customer.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'vehicleID'       => 'required|integer|exists:vehicles,vehicleID',
            'pickup_dateTime' => 'required|date',
            'return_dateTime' => 'required|date|after_or_equal:pickup_dateTime',
            'pickupAddress'   => 'required|string|max:100',
            'returnAddress'   => 'required|string|max:100',
            'voucherCode'     => 'nullable|integer',
            'pickup_other_location' => 'nullable|string|max:100',
            'return_other_location' => 'nullable|string|max:100',
        ]);

        // Override pickupAddress if "others" or "staff_office"
        $pickupAddress = $validated['pickupAddress'];
        if ($pickupAddress === 'others' || $pickupAddress === 'staff_office') {
            $pickupAddress = $request->input('pickup_other_location');
        }

        // Override returnAddress if "others" or "staff_office"
        $returnAddress = $validated['returnAddress'];
        if ($returnAddress === 'others' || $returnAddress === 'staff_office') {
            $returnAddress = $request->input('return_other_location');
        }

        $booking = Booking::create([
            'userID'          => auth()->id(),
            'vehicleID'       => $validated['vehicleID'],
            'pickup_dateTime' => $validated['pickup_dateTime'],
            'return_dateTime' => $validated['return_dateTime'],
            'pickupAddress'   => $pickupAddress,
            'returnAddress'   => $returnAddress,
            'voucherCode'     => $validated['voucherCode'],
            'bookingStatus'   => 'pending', // Sesuai dengan ENUM: 'pending'
        ]);

        return redirect()->route('payment.show', ['bookingID' => $booking->bookingID])
                        ->with('success', 'Booking Complete!');
    }

    public function updateStatus(Request $request, $bookingID): RedirectResponse
    {
        $request->validate([
            'status' => 'required|string|in:pending,successful,rejected',
        ]);

        $booking = Booking::findOrFail($bookingID);
        $status = strtolower(trim($request->input('status')));
        
        $booking->bookingStatus = $status;
        $booking->save();

        return back()->with('success', "Booking {$bookingID} updated to " . ucfirst($status));
    }

    public function reject($bookingID): RedirectResponse
    {
        $booking = Booking::findOrFail($bookingID);
        $booking->bookingStatus = 'rejected';
        $booking->save();

        return redirect()->back()->with('success', 'Booking has been rejected.');
    }
    //    public function reject($bookingID): RedirectResponse
    //{
    //    $booking = Booking::findOrFail($bookingID);
    //    $booking->bookingStatus = 'rejected';
    //    $booking->save();

    //    return redirect()->back()->with('success', 'Booking has been rejected.');
    //}

    /**
     * Reset booking to pending (additional method if needed)
     */
    public function resetToPending($bookingID): RedirectResponse
    {
        $booking = Booking::findOrFail($bookingID);
        $booking->bookingStatus = 'pending';
        $booking->save();

        return redirect()->back()->with('success', 'Booking has been reset to pending status.');
    }

    public function markPickup($id)
    {
        $booking = Booking::findOrFail($id);
        //$booking->bookingStatus = 'picked_up';
        //$booking->save();

        return back()->with('success', 'Booking marked as picked up.');
    }

    public function markReturn($id)
    {
        $booking = Booking::findOrFail($id);
        //$booking->bookingStatus = 'returned';
        //$booking->save();

        return back()->with('success', 'Booking marked as returned.');
    }
    public function pickupInspection($id)
    {
        $booking = Booking::findOrFail($id);
        return view('inspection.pickup', compact('booking'));
    }

    public function storePickupInspection(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        $request->validate([
            'carCondition'   => 'required|string',
            'mileage'        => 'required|integer|min:0',
            'fuel_level'     => 'required|integer|min:0|max:100',
            'fuel_evidence'  => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'damageDetected' => 'required|boolean',
            'notes'          => 'required|string',
            'front_view'     => 'required|file|mimes:jpg,jpeg,png|max:2048',
            'back_view'      => 'required|file|mimes:jpg,jpeg,png|max:2048',
            'right_view'     => 'required|file|mimes:jpg,jpeg,png|max:2048',
            'left_view'      => 'required|file|mimes:jpg,jpeg,png|max:2048',
        ]);

        $inspection = new Inspection();
        $inspection->vehicleID       = $booking->vehicleID;
        $inspection->carCondition    = $request->carCondition;
        $inspection->mileageReturned = $request->mileage;
        $inspection->fuelLevel       = $request->fuel_level;
        $inspection->damageDetected  = $request->damageDetected;
        $inspection->remark          = $request->notes;
        $inspection->inspectionType  = 'pickup';
        $inspection->staffID         = auth()->id();

        // required fuel evidence
        $inspection->fuel_evidence = $request->file('fuel_evidence')
            ->store('fuel_evidence', 'public');

        // optional photos
        foreach (['front_view','back_view','right_view','left_view'] as $field) {
            if ($request->hasFile($field)) {
                $inspection->$field = $request->file($field)->store('inspection_images', 'public');
            }
        }

        $inspection->save();

        return redirect()->route('customers.BookingView.pickupInspection', $booking->bookingID)
                        ->with('success', 'Pickup inspection recorded successfully.');
    }

    public function approve($bookingID)
    {

        $booking = Booking::findOrFail($bookingID);
        $booking->bookingStatus = 'successful';
        $booking->save();

        return redirect()->back()->with('success', 'Booking has been approved.');
    }

/*public function reject($bookingID): RedirectResponse
{
    $booking = Booking::findOrFail($bookingID);
    $booking->bookingStatus = 'rejected';
    $booking->save();

    return redirect()->back()->with('success', 'Booking has been rejected.');
}*/

    /**
     * Reset booking to pending (additional method if needed)
     */
    /*public function resetToPending($bookingID): RedirectResponse
    {
        $booking = Booking::findOrFail($bookingID);
        $booking->bookingStatus = 'pending';
        $booking->save();

        return redirect()->back()->with('success', 'Booking has been reset to pending status.');
    }*/
}
