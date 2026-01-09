<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Commission;
use Illuminate\Support\Str;

class CommissionController extends Controller
{
    public function index()
    {
        // Filter by logged-in user
        $userID = auth()->user()->userID; // GANTI: staffID -> userID
        $commissions = Commission::where('userID', $userID)->get(); // GANTI: staffID -> userID
        
        return view('staff.commission.index', compact('commissions'));
    }

    public function create()
    {
        return view('staff.commission.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'commissionType' => 'required|string|max:50',
            'appliedDate' => 'required|date',
            'amount' => 'required|integer|min:1',
        ]);

        // Generate ID dengan format: CO + 4 random alphanumeric
        $commissionID = 'CO' . strtoupper(Str::random(4));
        
        // Pastikan ID unique
        while (Commission::where('commissionID', $commissionID)->exists()) {
            $commissionID = 'CO' . strtoupper(Str::random(4));
        }

        $userID = auth()->user()->userID;

        Commission::create([
            'commissionID' => $commissionID,
            'commissionType' => $request->commissionType,
            'status' => 'approved',
            'appliedDate' => $request->appliedDate,
            'amount' => $request->amount,
            'userID' => $userID,
        ]);

        return redirect()->route('commission.index')->with('success', 'Commission added successfully!');
    }
    
    
    public function edit($id)
    {
        $commission = Commission::where('commissionID', $id)
            ->where('userID', auth()->user()->userID) // GANTI: staffID -> userID
            ->firstOrFail();
            
        return view('staff.commission.edit', compact('commission'));
    }

    public function update(Request $request, $id)
    {
        $commission = Commission::where('commissionID', $id)
            ->where('userID', auth()->user()->userID) // GANTI: staffID -> userID
            ->firstOrFail();

        $request->validate([
            'commissionType' => 'required|string|max:50',
            'appliedDate' => 'required|date',
            'amount' => 'required|integer|min:1',
        ]);

        $commission->update([
            'commissionType' => $request->commissionType,
            'appliedDate' => $request->appliedDate,
            'amount' => $request->amount,
        ]);

        return redirect()->route('commission.index')->with('success', 'Commission updated successfully!');
    }
    
    /*
    public function destroy($id)
    {
        $commission = Commission::where('commissionID', $id)
            ->where('userID', auth()->user()->userID) // GANTI: staffID -> userID
            ->firstOrFail();

        $commission->delete();

        return redirect()->route('commission.index')->with('success', 'Commission deleted successfully!');
    }
        */
}