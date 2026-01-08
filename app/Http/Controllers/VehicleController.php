<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vehicle;

class VehicleController extends Controller
{
    // Untuk customer browse (hanya available)
    public function index()
    {
        $vehicles = Vehicle::where('available', 1)->get(); // hanya available
        return view('browseVehicle', compact('vehicles'));
    }

    // Preview untuk homepage
    public function preview()
    {
        $vehicles = Vehicle::where('available', 1)->get();
        return view('welcome', compact('vehicles')); 
    }

    // Search availability
    public function search(Request $request)
    {
        $pickup = $request->input('pickup_dateTime');
        $return = $request->input('return_dateTime');

        if (empty($pickup) || empty($return)) {
            return view('browseVehicle', ['vehicles' => collect()]);
        }

        $vehicles = Vehicle::where('available', 1)
            ->whereDoesntHave('bookings', function($q) use ($pickup, $return) {
                $q->where('pickup_dateTime', '<', $return)
                  ->where('return_dateTime', '>', $pickup);
            })
            ->get();

        return view('browseVehicle', compact('vehicles', 'pickup', 'return'));
    }

    // Untuk admin
    public function indexAdmin(Request $request)
    {
        $filter = $request->query('filter');

        if ($filter === 'available') {
            $vehicles = Vehicle::where('available', 1)->get();
        } elseif ($filter === 'unavailable') {
            $vehicles = Vehicle::where('available', 0)->get();
        } else {
            $vehicles = Vehicle::all();
        }

        return view('admin.vehicle.indexVehicle', compact('vehicles'));
    }


    public function create()
    {
        return view('admin.vehicle.createVehicle');
    }

    public function edit($id)
    {
        $vehicle = Vehicle::findOrFail($id);
        return view('admin.vehicle.updateVehicle', compact('vehicle'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'vehicleName' => 'required|string',
            'plateNo' => 'required|string|unique:vehicles',
            'year' => 'required|integer',
            'price_per_day' => 'required|numeric',
            'price_per_hour' => 'required|numeric',
            'status' => 'required|in:available,unavailable',
            'image' => 'nullable|image|max:2048',
            'description' => 'nullable|string',
        ]);

        $vehicle = new Vehicle();
        $vehicle->vehicleName = $request->vehicleName;
        $vehicle->plateNo = $request->plateNo;
        $vehicle->year = $request->year;
        $vehicle->price_per_day = $request->price_per_day;
        $vehicle->price_per_hour = $request->price_per_hour;
        $vehicle->available = $request->status === 'available' ? 1 : 0;
        $vehicle->description = $request->description;

        if ($request->hasFile('image')) {
            $filename = time().'_'.$request->file('image')->getClientOriginalName();
            $request->file('image')->move(public_path('img'), $filename);
            $vehicle->image_url = $filename;
        }

        $vehicle->save();

        return redirect()->route('vehicles.index')->with('success', 'Vehicle created successfully');
    }

    public function update(Request $request, $id)
    {
        $vehicle = Vehicle::findOrFail($id);

        $request->validate([
            'vehicleName' => 'required|string',
            'plateNo' => 'required|string|unique:vehicles,plateNo,' . $vehicle->vehicleID . ',vehicleID',
            'year' => 'required|integer',
            'price_per_day' => 'required|numeric',
            'price_per_hour' => 'required|numeric',
            'status' => 'required|in:available,unavailable',
            'image' => 'nullable|image|max:2048',
            'description' => 'nullable|string',
        ]);

        $vehicle->vehicleName = $request->vehicleName;
        $vehicle->plateNo = $request->plateNo;
        $vehicle->year = $request->year;
        $vehicle->price_per_day = $request->price_per_day;
        $vehicle->price_per_hour = $request->price_per_hour;
        $vehicle->available = $request->status === 'available' ? 1 : 0;
        $vehicle->description = $request->description;

        if ($request->hasFile('image')) {
            $filename = time().'_'.$request->file('image')->getClientOriginalName();
            $request->file('image')->move(public_path('img'), $filename);
            $vehicle->image_url = $filename;
        }

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
