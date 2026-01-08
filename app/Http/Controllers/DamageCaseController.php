<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DamageCase;
use App\Models\Inspection;
use Illuminate\Support\Facades\Auth;


class DamageCaseController extends Controller
{
    // Senarai damage case
    public function index()
    {
        $cases = DamageCase::all();
        return view('staff.damage_case.index', compact('cases'));
    }

    // Papar form update
    public function edit($caseID)
    {
        $case = DamageCase::where('caseID', $caseID)->firstOrFail();
        $inspection = Inspection::find($case->inspectionID);

        return view('staff.damage_case.update', compact('case','inspection'));
    }

    // Simpan update damage case
    public function update(Request $request, $caseID)
    {
        $case = DamageCase::where('caseID', $caseID)->firstOrFail();

        $validated = $request->validate([
            'casetype'         => 'required|string',
            'filledby'         => 'required|string',
            'resolutionstatus' => 'required|in:Resolved,Unresolved',
        ]);

        $case->update($validated);

        return redirect()->route('damagecase.index')
            ->with('success', 'Damage case updated successfully!');
    }

    // Delete damage case (optional)
    public function destroy($caseID)
    {
        $case = DamageCase::where('caseID', $caseID)->firstOrFail();
        $case->delete();

        return redirect()->route('damagecase.index')
            ->with('success', 'Damage case deleted successfully!');
    }

    // Quick resolve button (optional)
    public function resolve($caseID)
    {
        $case = DamageCase::where('caseID', $caseID)->firstOrFail();
        $case->update(['resolutionstatus' => 'Resolved']);

        return redirect()->route('damagecase.index')
            ->with('success', 'Damage case resolved!');
    }
}
