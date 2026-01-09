<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Models\Vehicle;

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

        public function approve($bookingID)
    {
        $booking = Booking::findOrFail($bookingID);
        $booking->bookingStatus = 'successful'; // atau 'Approved' ikut DB
        $booking->save();

        return redirect()->back()->with('success', 'Booking has been approved.');
    }

    public function reject($bookingID)
    {
        $booking = Booking::findOrFail($bookingID);
        $booking->bookingStatus = 'rejected'; // ikut ENUM dalam DB
        $booking->save();

        return redirect()->back()->with('success', 'Booking has been rejected.');
    }

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
}