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

    // Papar form update
    public function edit($id)
    {
        $inspection = Inspection::findOrFail($id);
        return view('staff.inspection.update', compact('inspection'));
    }

    // Simpan update inspection
    public function update(Request $request, $id)
    {
        $inspection = Inspection::findOrFail($id);

        $validated = $request->validate([
            'vehicleID'        => 'required|integer|exists:vehicles,vehicleID',
            'carCondition'     => 'required|string',
            'mileageReturned'  => 'required|integer',
            'fuelLevel'        => 'required|integer',
            'damageDetected'   => 'required|boolean',
            'remark'           => 'nullable|string',
            'evidence'         => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Upload evidence jika ada
        if ($request->hasFile('evidence')) {
            $validated['evidence'] =
                $request->file('evidence')->store('evidence', 'public');
        }

        $inspection->update($validated);

        // Auto create damage case jika detect damage & belum ada
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

    // Delete inspection (optional)
    public function destroy($id)
    {
        $inspection = Inspection::findOrFail($id);
        $inspection->delete();

        return redirect()->route('inspection.index')
            ->with('success', 'Inspection deleted successfully!');
    }
}
