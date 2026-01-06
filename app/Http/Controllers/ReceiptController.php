<?php

// app/Http/Controllers/ReceiptController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;

class ReceiptController extends Controller
{
    public function view($bookingID)
    {
        // Get payment record
        $payment = DB::table('payment')
            ->where('bookingID', $bookingID)
            ->first();
            
        if (!$payment || empty($payment->receipt_file_path)) {
            abort(404, 'Receipt not found');
        }
        
        $filePath = storage_path('app/public/' . $payment->receipt_file_path);
        
        // Check if file exists
        if (!file_exists($filePath)) {
            abort(404, 'File not found at: ' . $filePath);
        }
        
        // Get file info
        $mimeType = mime_content_type($filePath);
        $fileName = basename($filePath);
        
        // Return file with appropriate headers
        return Response::make(file_get_contents($filePath), 200, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . $fileName . '"',
            'Cache-Control' => 'public, max-age=3600'
        ]);
    }
    
    public function download($bookingID)
    {
        // Get payment record
        $payment = DB::table('payment')
            ->where('bookingID', $bookingID)
            ->first();
            
        if (!$payment || empty($payment->receipt_file_path)) {
            abort(404, 'Receipt not found');
        }
        
        $filePath = storage_path('app/public/' . $payment->receipt_file_path);
        
        // Check if file exists
        if (!file_exists($filePath)) {
            abort(404, 'File not found');
        }
        
        return response()->download($filePath, basename($payment->receipt_file_path));
    }
}