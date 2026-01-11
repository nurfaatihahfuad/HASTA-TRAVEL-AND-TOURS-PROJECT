<?php

namespace App\Http\Controllers;

use App\Models\Commission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CommissionController extends Controller
{
    /**
     * Display a listing of commissions FOR STAFF
     */
    public function index()
    {
        // Staff only - jika admin cuba access, redirect ke admin page
        if (auth()->user()->role === 'admin') {
            return redirect()->route('admin.commission.index');
        }
        
        // STAFF VIEW: commissions sendiri sahaja
        $userID = auth()->user()->userID;
        $commissions = Commission::where('userID', $userID)
            ->orderBy('appliedDate', 'desc')
            ->get();
        
        return view('staff.commission.index', compact('commissions'));
    }

    /**
     * Display a listing of commissions FOR ADMIN
     */
    public function adminIndex(Request $request)
    {
        // Admin only - jika staff cuba access, redirect ke staff page
        /*
        if (auth()->user()->role !== 'admin') {
            return redirect()->route('staff.commission.index');
        }
        */
        
        // ADMIN VIEW: semua commissions dengan user
        $status = $request->query('status');
        
        $query = Commission::with('user');
        
        if ($status && in_array($status, ['pending', 'approved', 'rejected'])) {
            $query->where('status', $status);
        }
        
        $commissions = $query->orderBy('appliedDate', 'desc')->get();
        
        return view('admin.commissionVerify.index', compact('commissions'));
    }

    /**
     * Show the form for creating a new commission (STAFF ONLY)
     */
    public function create()
    {
        // Staff only - jika admin cuba access, redirect ke admin page
        if (auth()->user()->role === 'admin') {
            return redirect()->route('admin.commission.index');
        }
        
        return view('staff.commission.create');
    }

    /**
     * Store a newly created commission (STAFF ONLY)
     */
    public function store(Request $request)
    {
        // Staff only
        if (auth()->user()->role === 'admin') {
            return redirect()->route('admin.commission.index');
        }

        $request->validate([
            'commissionType' => 'required|string|max:50',
            'appliedDate' => 'required|date',
            'amount' => 'required|integer|min:1',
            'accountNumber' => 'required|string|max:20',
            'bankName' => 'required|string|max:100',
            'otherBankName' => 'required_if:bankName,Other|nullable|max:100',
            'receipt_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        // Handle bank name
        $bankName = $request->bankName;
        if ($request->bankName === 'Other' && $request->filled('otherBankName')) {
            $bankName = $request->otherBankName;
        }

        // Handle file upload
        $receiptFilePath = null;
        if ($request->hasFile('receipt_file')) {
            $file = $request->file('receipt_file');
            $fileName = 'receipt_' . time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('receipts', $fileName, 'public');
            $receiptFilePath = $path;
        }

        // Generate commission ID
        $commissionID = 'CO' . strtoupper(Str::random(4));
        while (Commission::where('commissionID', $commissionID)->exists()) {
            $commissionID = 'CO' . strtoupper(Str::random(4));
        }

        Commission::create([
            'commissionID' => $commissionID,
            'userID' => auth()->user()->userID,
            'commissionType' => $request->commissionType,
            'receipt_file_path' => $receiptFilePath,
            'status' => 'pending',
            'appliedDate' => $request->appliedDate,
            'amount' => $request->amount,
            'accountNumber' => $request->accountNumber,
            'bankName' => $bankName,
        ]);

        return redirect()->route('commission.index')
            ->with('success', 'Commission submitted successfully! Waiting for admin approval.');
    }

    /**
     * Display commission details FOR ADMIN
     */
    public function adminShow($id)
    {
        /*
        // Admin only
        if (auth()->user()->role !== 'admin') {
            return redirect()->route('staff.commission.index');
        }
        */

        $commission = Commission::with('user')
            ->where('commissionID', $id)
            ->firstOrFail();
        
        return view('admin.commission.show', compact('commission'));
    }

    /**
     * Show the form for editing commission (STAFF ONLY)
     */
    public function edit($id)
    {
        // Staff only
        if (auth()->user()->role === 'admin') {
            return redirect()->route('admin.commission.index');
        }

        $commission = Commission::where('commissionID', $id)
            ->where('userID', auth()->user()->userID)
            ->firstOrFail();
            
        return view('staff.commission.edit', compact('commission'));
    }

    /**
     * Update the specified commission (STAFF ONLY)
     */
    public function update(Request $request, $id)
    {
        // Staff only
        if (auth()->user()->role === 'admin') {
            return redirect()->route('admin.commission.index');
        }

        $commission = Commission::where('commissionID', $id)
            ->where('userID', auth()->user()->userID)
            ->firstOrFail();

        $request->validate([
            'commissionType' => 'required|string|max:50',
            'appliedDate' => 'required|date',
            'amount' => 'required|integer|min:1',
            'accountNumber' => 'required|string|max:20',
            'bankName' => 'required|string|max:100',
            'otherBankName' => 'required_if:bankName,Other|nullable|max:100',
            'receipt_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        // Handle bank name
        $bankName = $request->bankName;
        if ($request->bankName === 'Other' && $request->filled('otherBankName')) {
            $bankName = $request->otherBankName;
        }

        // Handle file upload
        $receiptFilePath = $commission->receipt_file_path;
        if ($request->hasFile('receipt_file')) {
            // Delete old file
            if ($receiptFilePath && Storage::disk('public')->exists($receiptFilePath)) {
                Storage::disk('public')->delete($receiptFilePath);
            }
            
            // Upload new file
            $file = $request->file('receipt_file');
            $fileName = 'receipt_' . time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('receipts', $fileName, 'public');
            $receiptFilePath = $path;
        }

        $commission->update([
            'commissionType' => $request->commissionType,
            'receipt_file_path' => $receiptFilePath,
            'appliedDate' => $request->appliedDate,
            'amount' => $request->amount,
            'accountNumber' => $request->accountNumber,
            'bankName' => $bankName,
            'status' => 'pending', // Reset status apabila update
        ]);

        return redirect()->route('commission.index')
            ->with('success', 'Commission updated successfully! Status reset to pending.');
    }

    /**
     * Approve commission (ADMIN ONLY)
     */
    public function approve($id)
    {
        // Admin only
        /*
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }
        */

        $commission = Commission::findOrFail($id);
        
        $commission->update([
            'status' => 'approved',
        ]);
        
        return redirect()->route('admin.commissionVerify.index')
            ->with('success', 'Commission #' . $commission->commissionID . ' approved successfully!');
    }

    /**
     * Reject commission (ADMIN ONLY)
     */
    public function reject($id)
    {
        // Admin only
        /*
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }
        */

        $commission = Commission::findOrFail($id);
        
        $commission->update([
            'status' => 'rejected',
        ]);
        
        return redirect()->route('admin.commissionVerify.index')
            ->with('success', 'Commission #' . $commission->commissionID . ' rejected!');
    }

    /**
     * Delete receipt (STAFF ONLY)
     */
    public function deleteReceipt($id)
    {
        // Staff only
        if (auth()->user()->role === 'admin') {
            abort(403);
        }

        $commission = Commission::where('commissionID', $id)
            ->where('userID', auth()->user()->userID)
            ->firstOrFail();

        if ($commission->receipt_file_path) {
            Storage::disk('public')->delete($commission->receipt_file_path);
            $commission->update(['receipt_file_path' => null]);
            
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false], 404);
    }
}