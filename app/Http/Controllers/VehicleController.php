<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vehicle;

class VehicleController extends Controller
{
    public function index()
    {   
        // Ambil semua vehicle yang available
        $vehicles = Vehicle::where('available', 1)->get();

        // Hantar ke view browseVehicle.blade.php
        return view('browseVehicle', compact('vehicles'));
    }

    public function preview() {
        $vehicles = Vehicle::where('available', 1)->get(); 
        return view('welcome', compact('vehicles')); 
    }

    public function search(Request $request) 
    {
        $pickup = $request->input('pickup_dateTime');
        $return = $request->input('return_dateTime');

        // Kalau tak isi tarikh langsung, jangan hantar apa-apa
        if (empty($pickup) || empty($return)) {
            return view('browseVehicle', ['vehicles' => collect()]);
        }

        // Ambil semua kereta yang available & tak clash dengan booking
        $vehicles = Vehicle::where('available', 1)
            ->whereDoesntHave('bookings', function($q) use ($pickup, $return) {
                $q->where('pickup_dateTime', '<', $return)
                ->where('return_dateTime', '>', $pickup);
            })
            ->get();

        return view('browseVehicle', compact('vehicles'));
    }



}
