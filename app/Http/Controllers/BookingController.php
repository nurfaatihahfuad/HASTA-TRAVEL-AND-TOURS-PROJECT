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

    public function create($vehicleID = null): View
    {
        $vehicle = null;

        if ($vehicleID) {
            $vehicle = Vehicle::findOrFail($vehicleID);
        }
        return view('booking', compact('vehicle'));

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
        'quantity'        => 'required|integer|min:1',
    ]);
    

    $booking = Booking::create([
        'userID'          => auth()->id(), // current logged-in user
        'vehicleID'       => $validated['vehicleID'],
        'pickup_dateTime' => $validated['pickup_dateTime'],
        'return_dateTime' => $validated['return_dateTime'],
        'pickupAddress'   => $validated['pickupAddress'],
        'returnAddress'   => $validated['returnAddress'],
        'voucherCode'     => $validated['voucherCode'] ?? null,
        'bookingStatus'   => 'Pending',
        ]);

        return redirect()->back()->with('success', 'Booking saved!');

}
}
