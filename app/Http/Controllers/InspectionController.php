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
    // Senarai inspection (staff & customer)
    public function index()
    {
        $inspections = Inspection::all();

        // view berbeza ikut role
        if (Auth::user()->role === 'customer') {
            return view('customer.inspection.index', compact('inspections'));
        }

        return view('staff.inspection.index', compact('inspections'));
    }

    // Customer: Papar form create inspection
    public function create()
    {
        if (Auth::user()->role !== 'customer') {
            abort(403, 'Unauthorized');
        }

        return view('customer.inspection.create');
    }

    // Customer: Simpan inspection baru
    public function store(Request $request)
    {
        if (Auth::user()->role !== 'customer') {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'vehicleID'       => 'required|integer|exists:vehicles,vehicleID',
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

        Inspection::create($validated);

        return redirect()->route('inspection.index')
            ->with('success', 'Inspection created successfully!');
    }

    // Staff & Customer: Papar form edit
    public function edit($id)
    {
        $inspection = Inspection::findOrFail($id);

        if (Auth::user()->role === 'customer') {
            return view('customer.inspection.update', compact('inspection'));
        }

        return view('staff.inspection.update', compact('inspection'));
    }

    // Staff & Customer: Simpan update
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

        if ($request->hasFile('evidence')) {
            $validated['evidence'] = $request->file('evidence')->store('evidence', 'public');
        }

        $inspection->update($validated);

        // Staff: auto create damage case jika detect damage
        if (Auth::user()->role === 'staff' && $inspection->damageDetected && !$inspection->damageCase) {
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

    // Staff sahaja: delete inspection
    public function destroy($id)
    {
        if (Auth::user()->role !== 'staff') {
            abort(403, 'Unauthorized');
        }

        $inspection = Inspection::findOrFail($id);
        $inspection->delete();

        return redirect()->route('inspection.index')
            ->with('success', 'Inspection deleted successfully!');
    }
}