<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\Vehicle;

class BookingController extends Controller
{
    /**
     * Show the booking form to the customer.
     */

    
    public function create($vehicle_id = null): View
    {
        $vehicle = null;
        if ($vehicle_id) 
        {
            $vehicle = Vehicle::findOrFail($vehicle_id);
             
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
        'pickup_dateTime' => 'required|date',
        'return_dateTime' => 'required|date|after_or_equal:pickup_dateTime',
        'pickupAddress'   => 'required|string|max:255',
        'returnAddress'   => 'required|string|max:255',
        'voucherCode'     => 'nullable|string|max:50',
        'bookingStatus'   => 'required|string|max:50',
        'quantity'        => 'required|integer|min:1',
    ]);

    Booking::create([
    'userID'          => auth()->id(), // current logged-in user
    'vehicleID'       => $request->vehicle_id,
    'pickup_dateTime' => $request->pickup_dateTime,
    'return_dateTime' => $request->return_dateTime,
    'pickupAddress'   => $request->pickupAddress,
    'returnAddress'   => $request->returnAddress,
    'voucherCode'     => $request->voucherCode,
    'bookingStatus'   => 'Pending',
    'quantity'        => $request->quantity,
]);

}
}
