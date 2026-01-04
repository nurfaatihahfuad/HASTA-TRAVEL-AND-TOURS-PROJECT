<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Inspection;
use App\Models\DamageCase;
use App\Models\Blacklist;
use App\Models\Booking;

class InspectionController extends Controller
{
    public function store(Request $request)
    {
        $inspection = Inspection::create([
            'inspDate'        => $request->inspDate,
            'carCondition'    => $request->carCondition,
            'mileageReturned' => $request->mileageReturned,
            'fuelLevel'       => $request->fuelLevel,
            'damageDetected'  => $request->damageDetected === 'yes',
            'remark'          => $request->remark,
            'evidence'        => $request->evidence,
            'userID'          => Auth::id(),
            'roleName'        => Auth::user()->role,
            'bookingID'       => $request->bookingID,
        ]);

        if ($inspection->damageDetected) {
            DamageCase::create([
                'casetype'        => 'return',
                'filledby'        => Auth::user()->name,
                'resolutionstatus'=> 'open',
                'inspectionid'    => $inspection->inspectionid,
            ]);

            $booking = Booking::find($request->bookingID);

            if ($booking) {
                Blacklist::create([
                    'customerUID' => $booking->customer_id,
                    'reason'      => 'Damage detected during return',
                ]);
            }
        }

        // Tukar redirect ikut role staff
        return redirect()->route('staff_salesperson.dashboard')
                         ->with('success', 'Inspection submitted.');
    }

    public function index()
    {
        return view('inspection'); // blade form inspection.blade.php
    }
}