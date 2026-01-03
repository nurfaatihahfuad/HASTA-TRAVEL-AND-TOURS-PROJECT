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
    public function create($vehicleID): View
    {
        $vehicle = Vehicle::findOrFail($vehicleID);
        return view('booking', compact('vehicle'));

        //$vehicle = Vehicle::find(1);
    }

    /**
     * Store the booking submitted by the customer.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            // corrected: use 'id' if vehicles table PK is 'id'
            'vehicleID'       => 'required|integer|exists:vehicles,id',
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
            'quantity'        => $validated['quantity'],
            'bookingStatus'   => 'Pending',
        ]);

<<<<<<< HEAD
}
}
=======
        // corrected: redirect to a proper route instead of back()
        return redirect()->route('customer.dashboard')->with('success', 'Booking saved!');
    }
}
>>>>>>> e23fe2e4c766eaa984ffdc702e52e92869e16be3
