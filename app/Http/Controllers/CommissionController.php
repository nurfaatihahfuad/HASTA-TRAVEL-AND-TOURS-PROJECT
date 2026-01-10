<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Commission;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class CommissionController extends Controller
{
    public function index()
    {
        $userID = auth()->user()->userID;
        $commissions = Commission::where('userID', $userID)->get();
        
        // Set semua status kepada pending untuk view sahaja
        foreach ($commissions as $commission) {
            $commission->status = 'pending'; // Override status
        }
        
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
            'accountNumber' => 'required|string|max:20',
            'bankName' => 'required|string|max:100',
            'otherBankName' => 'required_if:bankName,Other|nullable|max:100',
            'receipt_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240', // Validation untuk receipt
        ]);

        // Handle bank name (jika pilih "Other", guna otherBankName)
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
            'receipt_file_path' => $receiptFilePath, // Simpan path receipt
            'status' => 'pending',
            'appliedDate' => $request->appliedDate,
            'amount' => $request->amount,
            'accountNumber' => $request->accountNumber,
            'bankName' => $bankName,
            'userID' => $userID,
        ]);

        return redirect()->route('commission.index')
            ->with('success', 'Commission submitted successfully! Waiting for admin approval.');
    }
    
    public function edit($id)
    {
        $commission = Commission::where('commissionID', $id)
            ->where('userID', auth()->user()->userID)
            ->firstOrFail();
            
        return view('staff.commission.edit', compact('commission'));
    }

    public function update(Request $request, $id)
    {
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

        // Handle bank name (jika pilih "Other", guna otherBankName)
        $bankName = $request->bankName;
        if ($request->bankName === 'Other' && $request->filled('otherBankName')) {
            $bankName = $request->otherBankName;
        }

        // Handle file upload jika ada file baru
        $receiptFilePath = $commission->receipt_file_path;
        if ($request->hasFile('receipt_file')) {
            // Delete old file jika ada
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
            'status' => 'pending', // Reset status ke pending apabila update
        ]);

        return redirect()->route('commission.index')
            ->with('success', 'Commission updated successfully! Status reset to pending.');
    }

    // Optional: Function untuk delete receipt
    public function deleteReceipt($id)
    {
        $commission = Commission::where('commissionID', $id)
            ->where('userID', auth()->user()->userID)
            ->firstOrFail();

        if ($commission->receipt_file_path) {
            Storage::disk('public')->delete($commission->receipt_file_path);
            $commission->update(['receipt_file_path' => null]);
            
            return back()->with('success', 'Receipt deleted successfully.');
        }

        return back()->with('error', 'No receipt found.');
    }
}