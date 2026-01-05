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

    /*
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
        */

    // VehicleController@search
    public function search(Request $request)
    {
        $pickup_dateTime = $request->input('pickup_dateTime');
        $return_dateTime = $request->input('return_dateTime');

        $vehicles = Vehicle::where('available', 1)
            ->whereNotIn('vehicleID', function($q) use ($pickup_dateTime, $return_dateTime) {
                $q->select('vehicleID')
                ->from('booking')
                ->where(function($q2) use ($pickup_dateTime, $return_dateTime) {
                    $q2->whereBetween('pickup_dateTime', [$pickup_dateTime, $return_dateTime])
                    ->orWhereBetween('return_dateTime', [$pickup_dateTime, $return_dateTime]);
                });
            })
            ->get();

        return view('browseVehicle', compact('vehicles', 'pickup_dateTime', 'return_dateTime'));
    }

    public function indexAdmin()
    {
        // Ambil semua vehicle (tak kisah available/unavailable)
        $vehicles = Vehicle::all();

        // Hantar ke view admin/vehicle/indexVehicle.blade.php
        return view('admin.vehicle.indexVehicle', compact('vehicles'));
    }


    public function store(Request $request)
    {
        $vehicle = new Vehicle();
        $vehicle->vehicleName = $request->vehicleName;
        $vehicle->type = $request->type;
        $vehicle->status = $request->status;
        $vehicle->available = $request->status === 'available' ? 1 : 0;
        $vehicle->save();

        return redirect()->route('vehicles.index')->with('success', 'Vehicle created successfully');
    }

    public function edit($id)
    {
        $vehicle = Vehicle::findOrFail($id);
        return view('updateVehicle', compact('vehicle'));
    }

    public function update(Request $request, $id)
    {
        $vehicle = Vehicle::findOrFail($id);
        $vehicle->vehicleName = $request->vehicleName;
        $vehicle->type = $request->type;
        $vehicle->status = $request->status;
        $vehicle->available = $request->status === 'available' ? 1 : 0;
        $vehicle->save();

        return redirect()->route('vehicles.index')->with('success', 'Vehicle updated successfully');
    }

    public function destroy($id)
    {
        $vehicle = Vehicle::findOrFail($id);
        $vehicle->delete();

        return redirect()->route('vehicles.index')->with('success', 'Vehicle deleted successfully');
    }

}
