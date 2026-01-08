<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Inspection;
use App\Models\DamageCase;
use App\Models\Booking;
use App\Models\Vehicle;

class InspectionController extends Controller
{
    // Senarai semua inspection
    public function index()
    {
        $inspections = Inspection::all();
        return view('staff.inspection.index', compact('inspections'));
    }

    // Papar form create inspection
    public function create()
    {
        // Ambil semua booking yang aktif, termasuk data vehicle
        $bookings = Booking::with('vehicle')
                    ->whereIn('bookingStatus', ['active', 'pending'])
                    ->whereHas('vehicle') // pastikan ada vehicle
                    ->orderBy('pickup_dateTime', 'asc')
                    ->get();

        return view('staff.inspection.create', compact('bookings'));
    }

    // Papar form edit inspection
    public function edit($id)
    {
        $inspection = Inspection::findOrFail($id);

        $bookings = Booking::with('vehicle')
                    ->where('bookingStatus', 'active')
                    ->whereHas('vehicle')
                    ->orderBy('pickup_dateTime', 'asc')
                    ->get();

        return view('staff.inspection.update', compact('inspection', 'bookings'));
    }

    // Simpan inspection baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'vehicleID'       => 'required|exists:vehicles,vehicleID',
            'carCondition'    => 'required|string',
            'mileageReturned' => 'required|integer',
            'fuelLevel'       => 'required|integer',
            'damageDetected'  => 'required|boolean',
            'remark'          => 'nullable|string',
            'evidence'        => 'required|file|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Upload evidence file
        $evidencePath = $request->file('evidence')->store('evidence', 'public');

        $inspectionData = [
            'vehicleID'       => $validated['vehicleID'],
            'staffID'         => Auth::user()->userID,
            'carCondition'    => $validated['carCondition'],
            'mileageReturned' => $validated['mileageReturned'],
            'fuelLevel'       => $validated['fuelLevel'],
            'damageDetected'  => $validated['damageDetected'],
            'remark'          => $validated['remark'],
            'evidence'        => $evidencePath,
        ];

        $inspection = Inspection::create($inspectionData);

        // Auto create damage case jika damageDetected = true
        if ($inspection->damageDetected) {
            return redirect()->route('damagecase.create', ['inspectionID' => $inspection->inspectionID])
                             ->with('success', 'Inspection created, please fill damage case!');
        }

        return redirect()->route('inspection.index')
                         ->with('success', 'Inspection created successfully!');
    }

    // Update inspection
    public function update(Request $request, $id)
    {
        $inspection = Inspection::findOrFail($id);

        $validated = $request->validate([
            'vehicleID'       => 'required|exists:vehicles,vehicleID',
            'carCondition'    => 'required|string',
            'mileageReturned' => 'required|integer',
            'fuelLevel'       => 'required|integer',
            'damageDetected'  => 'required|boolean',
            'remark'          => 'nullable|string',
            'evidence'        => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('evidence')) {
            $validated['evidence'] = $request->file('evidence')->store('evidence', 'public');
        }

        $inspection->update($validated);

        if ($inspection->damageDetected && !$inspection->damageCase) {
            DamageCase::create([
                'inspectionID'     => $inspection->inspectionID,
                'casetype'         => 'Collision Damage',
                'filledby'         => Auth::user()->name ?? 'System',
                'resolutionstatus' => 'Unresolved',
            ]);
        }

        return redirect()->route('inspection.index')
                         ->with('success', 'Inspection updated successfully!');
    }

    // Delete inspection
    public function destroy($id)
    {
        $inspection = Inspection::findOrFail($id);
        $inspection->delete();

        return redirect()->route('inspection.index')
                         ->with('success', 'Inspection deleted successfully!');
    }
}
