<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DamageCase;
use App\Models\Inspection;
use Illuminate\Support\Facades\Auth;

class DamageCaseController extends Controller
{
    /**
     * Display a listing of the damage cases.
     */
    public function index()
    {
        $cases = DamageCase::all();
        return view('staff.damage_case.index', compact('cases'));
    }

    /**
     * Show the form for creating a new damage case.
     */
    public function create(Request $request)
{
    $inspectionID = $request->inspectionID; // dapat dari redirect
    $inspection   = Inspection::findOrFail($inspectionID);

    return view('staff.damage_case.create', compact('inspection'));
}

    /**
     * Store a newly created damage case in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'inspectionID'     => 'required|integer|exists:inspection,inspectionID',
            'casetype'         => 'required|string',
            'filledby'         => 'required|string',
            'resolutionstatus' => 'required|in:Resolved,Unresolved',
        ]);

        DamageCase::create($validated);

        return redirect()->route('damagecase.index')
                         ->with('success','Damage case created successfully!');
    }

    /**
     * Show the form for editing the specified damage case.
     */
    public function edit($caseID)
    {
        // gunakan caseID sebagai PK
        $case = DamageCase::where('caseID', $caseID)->firstOrFail();
        $inspections = Inspection::all();

        return view('staff.damage_case.update', compact('case','inspections'));
    }

    /**
     * Update the specified damage case.
     */
    public function update(Request $request, $caseID)
    {
        $case = DamageCase::where('caseID', $caseID)->firstOrFail();

        // kalau inspectionID readonly, boleh buang validation exists
        $validated = $request->validate([
            'casetype'         => 'required|string',
            'filledby'         => 'required|string',
            'resolutionstatus' => 'required|in:Resolved,Unresolved',
        ]);

        $case->update($validated);

        return redirect()->route('damagecase.index')
                         ->with('success','Damage case updated successfully!');
    }

    /**
     * Remove the specified damage case from storage.
     */
    public function destroy($caseID)
    {
        $case = DamageCase::where('caseID', $caseID)->firstOrFail();
        $case->delete();

        return redirect()->route('damagecase.index')
                         ->with('success','Damage case deleted successfully!');
    }

    /**
     * Mark the specified damage case as resolved.
     */
    public function resolve($caseID)
    {
        $case = DamageCase::where('caseID', $caseID)->firstOrFail();
        $case->update(['resolutionstatus' => 'Resolved']);

        return redirect()->route('damagecase.index')
                         ->with('success','Damage case resolved!');
    }
}