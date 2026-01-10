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
            'carCondition'   => 'required|string',
            'mileage'        => 'required|integer|min:0',
            'fuel_level'     => 'required|integer|min:0|max:100',
            'fuel_evidence'  => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'damageDetected' => 'required|boolean',
            'notes'          => 'required|string',
            'front_view'     => 'required|file|mimes:jpg,jpeg,png|max:2048',
            'back_view'      => 'required|file|mimes:jpg,jpeg,png|max:2048',
            'right_view'     => 'required|file|mimes:jpg,jpeg,png|max:2048',
            'left_view'      => 'required|file|mimes:jpg,jpeg,png|max:2048',
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

    // ============================
    // Pickup Inspection
    // ============================
    public function pickupInspection($id)
    {
        $booking = Booking::findOrFail($id);
        return view('inspection.pickup', compact('booking'));
    }

    public function storePickupInspection(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        Inspection::create([
        'carCondition'    => $request->carCondition,
        'mileageReturned' => $request->mileage,
        'fuelLevel'       => $request->fuel_level,
        'fuel_evidence'   => $request->file('fuel_evidence')
                                    ? $request->file('fuel_evidence')->store('fuel_evidence', 'public')
                                    : null, // wajib isi, jadi pastikan form ada input file

        'damageDetected'  => $request->damageDetected,
        'remark'          => $request->notes,

        // image uploads (optional tapi ikut schema awak)
        'front_view'      => $request->file('front_view')
                                    ? $request->file('front_view')->store('inspection_images', 'public')
                                    : null,
        'back_view'       => $request->file('back_view')
                                    ? $request->file('back_view')->store('inspection_images', 'public')
                                    : null,
        'right_view'      => $request->file('right_view')
                                    ? $request->file('right_view')->store('inspection_images', 'public')
                                    : null,
        'left_view'       => $request->file('left_view')
                                    ? $request->file('left_view')->store('inspection_images', 'public')
                                    : null,

        'vehicleID'       => $booking->vehicleID,
        'staffID'         => auth()->id(), // kalau staff login
        'inspectionType'  => 'pickup',     // atau 'return'
    ]);
        return redirect()->route('inspection.pickupInspection', $booking->bookingID)
                 ->with('success', 'Pickup inspection recorded successfully.');
    }

    // ============================
    // Return Inspection
    // ============================
    public function returnInspection($id)
    {
        $booking = Booking::findOrFail($id);
        return view('inspection.return', compact('booking'));
    }

    public function storeReturnInspection(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        Inspection::create([
        'carCondition'    => $request->carCondition,
        'mileageReturned' => $request->mileage,
        'fuelLevel'       => $request->fuel_level,
        'fuel_evidence'   => $request->file('fuel_evidence')
                                    ? $request->file('fuel_evidence')->store('fuel_evidence', 'public')
                                    : null, // wajib isi, jadi pastikan form ada input file

        'damageDetected'  => $request->damageDetected,
        'remark'          => $request->notes,

        // image uploads (optional tapi ikut schema awak)
        'front_view'      => $request->file('front_view')
                                    ? $request->file('front_view')->store('inspection_images', 'public')
                                    : null,
        'back_view'       => $request->file('back_view')
                                    ? $request->file('back_view')->store('inspection_images', 'public')
                                    : null,
        'right_view'      => $request->file('right_view')
                                    ? $request->file('right_view')->store('inspection_images', 'public')
                                    : null,
        'left_view'       => $request->file('left_view')
                                    ? $request->file('left_view')->store('inspection_images', 'public')
                                    : null,

        'vehicleID'       => $booking->vehicleID,
        'staffID'         => auth()->id(), // kalau staff login
        'inspectionType'  => 'return',     // atau 'return'
    ]);
        return redirect()->route('inspection.returnInspection', $booking->bookingID)
                 ->with('success', 'Return inspection recorded successfully.');
    }
}
