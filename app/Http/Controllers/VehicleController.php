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

    public function preview() { // Ambil 3 kereta pertama dari DB 
        $vehicles = Vehicle::where('available', 1)->take(3)->get(); 
        return view('welcome', compact('vehicles')); 
    }

    public function search(Request $request) { 
        $query = $request->input('q'); 
        if (!empty($query)) {
             $vehicles = Vehicle::where('model', 'LIKE', "%{$query}%") 
                ->orWhere('brand', 'LIKE', "%{$query}%") 
                ->get(); 
        } else { 
            $vehicles = Vehicle::where('available', 1)->get(); 
        } 
        return view('browseVehicle', compact('vehicles', 'query')); }
}
