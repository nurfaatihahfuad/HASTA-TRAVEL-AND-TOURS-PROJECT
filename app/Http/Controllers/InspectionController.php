<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Inspection;
use App\Models\DamageCase;
use App\Models\BlacklistedCust;
use App\Models\Booking;

class InspectionController extends Controller
{
    // Senarai inspection
    public function index()
    {
        $inspections = Inspection::all();
        return view('staff.inspection.index', compact('inspections'));
    }

    // Papar form create
    public function create()
    {
        $bookings = Booking::all();
        return view('staff.inspection.create', compact('bookings'));
    }

    // Papar form edit
    public function edit($id)
    {
        $inspection = Inspection::findOrFail($id);
        return view('staff.inspection.update', compact('inspection'));
    }

    public function store(Request $request)
{
    $validated = $request->validate([
        'vehicleID'       => 'required|integer|exists:vehicles,vehicleID',
        'carCondition'    => 'required|string',
        'mileageReturned' => 'required|integer',
        'fuelLevel'       => 'required|integer',
        'damageDetected'  => 'required|boolean',
        'remark'          => 'nullable|string',
        'evidence'        => 'required|file|mimes:jpg,jpeg,png|max:2048',
    ]);

    // Upload evidence file (now required)
    $evidencePath = $request->file('evidence')->store('evidence', 'public');


    $inspectionData = [
            'vehicleID'      => $validated['vehicleID'], // Get vehicleID from booking
            'staffID'        => Auth::user()->userID,
            'carCondition'   => $validated['carCondition'],
            'mileageReturned'=> $validated['mileageReturned'],
            'fuelLevel'      => $validated['fuelLevel'],
            'damageDetected' => $validated['damageDetected'],
            'remark'         => $validated['remark'],
            'evidence'       => $evidencePath,
        ];

    // Simpan evidence file kalau ada
    /*if ($request->hasFile('evidence')) {
        $validated['evidence'] = $request->file('evidence')->store('evidence', 'public');
    }*/

    // Simpan inspection
    $inspection = Inspection::create($inspectionData);

    // 🚀 Auto create damage case kalau damageDetected = true
     // 🚀 Kalau damageDetected = true, terus redirect ke damage case create
    if ($inspection->damageDetected) {
        return redirect()->route('damagecase.create', ['inspectionID' => $inspection->inspectionID])
                         ->with('success', 'Inspection created, please fill damage case!');
    }

    return redirect()->route('inspection.index')
                     ->with('success', 'Inspection created successfully!');
}



public function update(Request $request, $id)
{
    $inspection = Inspection::findOrFail($id);

    $validated = $request->validate([
        'vehicleID'      => 'required|integer|exists:vehicles,vehicleID',
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

    // 🚀 Auto create damage case kalau damageDetected = true dan belum ada damage case
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

// app/Http/Controllers/InspectionController.php

public function destroy($id)
{
    $inspection = Inspection::findOrFail($id);
    $inspection->delete();

    return redirect()->route('inspection.index')
                     ->with('success', 'Inspection deleted successfully!');
}

}