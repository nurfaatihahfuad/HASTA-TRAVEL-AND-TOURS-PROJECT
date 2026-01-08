<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
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

        return view('booking', compact('vehicle', 'pickup_dateTime', 'return_dateTime'));

        //$vehicle = Vehicle::find(1);
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
            'pickupAddress'   => 'required|string|max:255',
            'returnAddress'   => 'required|string|max:255',
            'voucherCode'     => 'nullable|string|max:50',
        ]);

        $booking = Booking::create([
            'userID'          => auth()->id(), // current logged-in user
            'vehicleID'       => $validated['vehicleID'],
            'pickup_dateTime' => $validated['pickup_dateTime'],
            'return_dateTime' => $validated['return_dateTime'],
            'pickupAddress'   => $validated['pickupAddress'],
            'returnAddress'   => $validated['returnAddress'],
            'voucherCode'     => $validated['voucherCode'],
            'bookingStatus'   => 'Pending',
        ]);

        // corrected: redirect to a proper route instead of back()
        return redirect()->route('payment.show', ['bookingID' => $booking->bookingID])
                         ->with('success', 'Booking Complete!');
        //return redirect()->route('payment.show', $booking->id); 
    }

    //Auni tambah
    public function updateStatus(Request $request, $bookingID)
    {
        $booking = Booking::findOrFail($bookingID);
        //Auni tambah ni
        $status = $request->input('status');
        // yg bawah ni Auni tambah
        $booking->bookingStatus = $status; // 'approved' atau 'rejected'
        $booking->save();

        return back()->with('success', "Booking {$bookingID} updated to {$request->status}");
    }

        public function approve($bookingID)
    {
        $booking = Booking::findOrFail($bookingID);
        $booking->bookingStatus = 'successful'; // ikut ENUM dalam DB
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
}

