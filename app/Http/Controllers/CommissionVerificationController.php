<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Commission;
use Illuminate\Http\Request;

class CommissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function index()
    {
        // Hanya ambil commissions yang ada userID dan eager load user
        $commissions = Commission::whereNotNull('userID')
            ->where('userID', '!=', '')
            ->with(['user' => function($query) {
                $query->withTrashed();
            }])
            ->orderBy('appliedDate', 'desc')
            ->get();
        
        return view('admin.commission.index', compact('commissions'));
    }

    public function show($id)
    {
        $commission = Commission::with('user')
            ->where('commissionID', $id)
            ->firstOrFail();
        
        return view('admin.commission.show', compact('commission'));
    }

    // Approve commission
    public function approve($id)
    {
        $commission = Commission::findOrFail($id);
        
        $commission->update([
            'status' => 'approved',
        ]);
        
        return redirect()->route('admin.commission.index')
            ->with('success', 'Commission #' . $commission->commissionID . ' approved successfully!');
    }

    // Reject commission
    public function reject($id)
    {
        $commission = Commission::findOrFail($id);
        
        $commission->update([
            'status' => 'rejected',
        ]);
        
        return redirect()->route('admin.commission.index')
            ->with('success', 'Commission #' . $commission->commissionID . ' rejected!');
    }
}