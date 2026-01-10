<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Inspection;
use App\Models\DamageCase;
use App\Models\Booking;
use Illuminate\Support\Facades\Log;

class InspectionController extends Controller
{
    /**
     * Menampilkan senarai rekod inspection untuk Customer.
     */
    public function index()
    {
        $inspections = Inspection::with(['booking', 'vehicle'])
            ->whereHas('booking', function($query) {
                $query->where('userID', auth()->user()->userID);
            })
            ->latest()
            ->get();

        return view('customers.inspections', compact('inspections'));
    }

    /**
     * Tampilkan borang Pickup (GET).
     */
    public function pickupInspection($id)
    {
        $booking = Booking::where('bookingID', $id)->firstOrFail();
        return view('inspection.pickup', compact('booking'));
    }

    /**
     * Simpan data Pickup (POST) - FIXED VERSION.
     */
    public function storePickupInspection(Request $request, $id)
    {
        try {
            Log::info('=== PICKUP INSPECTION START ===');
            Log::info('Booking ID: ' . $id);
            Log::info('User: ' . auth()->user()->userID);
            Log::info('Request Data: ', $request->all());
            
            $booking = Booking::findOrFail($id);
            
            // Validate without files first for testing
            $validated = $request->validate([
                'carCondition'    => 'required|string|min:2',
                'mileageReturned' => 'required|integer|min:0',
                'fuelLevel'       => 'required|integer|min:0|max:100',
                'damageDetected'  => 'required|in:0,1',
                'remark'          => 'required|string|min:3',
            ]);
            
            Log::info('Validated Data: ', $validated);
            
            // Handle file uploads if present, otherwise use defaults
            $fuelEvidence = 'default_fuel.jpg';
            $frontView = 'default_front.jpg';
            $backView = 'default_back.jpg';
            $rightView = 'default_right.jpg';
            $leftView = 'default_left.jpg';
            
            if ($request->hasFile('fuel_evidence')) {
                $fuelEvidence = $request->file('fuel_evidence')->store('evidence', 'public');
            }
            if ($request->hasFile('front_view')) {
                $frontView = $request->file('front_view')->store('inspection', 'public');
            }
            if ($request->hasFile('back_view')) {
                $backView = $request->file('back_view')->store('inspection', 'public');
            }
            if ($request->hasFile('right_view')) {
                $rightView = $request->file('right_view')->store('inspection', 'public');
            }
            if ($request->hasFile('left_view')) {
                $leftView = $request->file('left_view')->store('inspection', 'public');
            }
            
            // Create inspection record
            $inspectionData = [
                'bookingID'       => $booking->bookingID,
                'vehicleID'       => $booking->vehicleID,
                'staffID'         => auth()->user()->userID, // FIXED: userID bukan id()
                'inspectionType'  => 'pickup',
                'carCondition'    => $validated['carCondition'],
                'mileageReturned' => (int) $validated['mileageReturned'],
                'fuelLevel'       => (int) $validated['fuelLevel'],
                'damageDetected'  => $validated['damageDetected'] == '1',
                'remark'          => $validated['remark'],
                'fuel_evidence'   => $fuelEvidence,
                'front_view'      => $frontView,
                'back_view'       => $backView,
                'right_view'      => $rightView,
                'left_view'       => $leftView,
            ];
            
            Log::info('Creating inspection with data: ', $inspectionData);
            
            $inspection = Inspection::create($inspectionData);
            
            Log::info('Inspection Created ID: ' . $inspection->id);
            
            // Update booking status
            $booking->update(['status' => 'pickup_inspected']);
            
            Log::info('=== PICKUP INSPECTION END ===');
            
            return redirect()->route('customer.inspections.index')
                ->with('success', 'Pickup inspection recorded successfully!');
                
        } catch (\Exception $e) {
            Log::error('Pickup Inspection Error: ' . $e->getMessage());
            Log::error('Error Trace: ' . $e->getTraceAsString());
            
            return redirect()->back()
                ->with('error', 'Error: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Tampilkan borang Return (GET).
     */
    public function returnInspection($id)
    {
        $booking = Booking::findOrFail($id);
        return view('inspection.return', compact('booking'));
    }

    /**
     * Simpan data Return (POST) - FIXED VERSION.
     */
    public function storeReturnInspection(Request $request, $id)
    {
        try {
            Log::info('=== RETURN INSPECTION START ===');
            Log::info('Booking ID: ' . $id);
            Log::info('User: ' . auth()->user()->userID);
            Log::info('Request Data: ', $request->all());
            
            $booking = Booking::findOrFail($id);
            
            // Validate without files first for testing
            $validated = $request->validate([
                'carCondition'    => 'required|string|min:2',
                'mileageReturned' => 'required|integer|min:0',
                'fuelLevel'       => 'required|integer|min:0|max:100',
                'damageDetected'  => 'required|in:0,1',
                'remark'          => 'required|string|min:3',
            ]);
            
            Log::info('Validated Data: ', $validated);
            
            // Handle file uploads if present, otherwise use defaults
            $fuelEvidence = 'default_fuel.jpg';
            $frontView = 'default_front.jpg';
            $backView = 'default_back.jpg';
            $rightView = 'default_right.jpg';
            $leftView = 'default_left.jpg';
            
            if ($request->hasFile('fuel_evidence')) {
                $fuelEvidence = $request->file('fuel_evidence')->store('evidence', 'public');
            }
            if ($request->hasFile('front_view')) {
                $frontView = $request->file('front_view')->store('inspection', 'public');
            }
            if ($request->hasFile('back_view')) {
                $backView = $request->file('back_view')->store('inspection', 'public');
            }
            if ($request->hasFile('right_view')) {
                $rightView = $request->file('right_view')->store('inspection', 'public');
            }
            if ($request->hasFile('left_view')) {
                $leftView = $request->file('left_view')->store('inspection', 'public');
            }
            
            // Create inspection record
            $inspectionData = [
                'bookingID'       => $booking->bookingID,
                'vehicleID'       => $booking->vehicleID,
                'staffID'         => auth()->user()->userID, // FIXED: userID bukan id()
                'inspectionType'  => 'return',
                'carCondition'    => $validated['carCondition'],
                'mileageReturned' => (int) $validated['mileageReturned'],
                'fuelLevel'       => (int) $validated['fuelLevel'],
                'damageDetected'  => $validated['damageDetected'] == '1',
                'remark'          => $validated['remark'],
                'fuel_evidence'   => $fuelEvidence,
                'front_view'      => $frontView,
                'back_view'       => $backView,
                'right_view'      => $rightView,
                'left_view'       => $leftView,
            ];
            
            Log::info('Creating return inspection with data: ', $inspectionData);
            
            $inspection = Inspection::create($inspectionData);
            
            Log::info('Return Inspection Created ID: ' . $inspection->id);
            
            // Automatically create DamageCase if damage detected during RETURN
            if ($inspection->damageDetected) {
                DamageCase::create([
                    'inspectionID'     => $inspection->inspectionID,
                    'casetype'         => 'Collision Damage',
                    'filledby'         => auth()->user()->name,
                    'resolutionstatus' => 'Unresolved',
                ]);
                Log::info('Damage case created for inspection ID: ' . $inspection->inspectionID);
            }
            
            // Update booking status
            $booking->update(['status' => 'completed']);
            
            Log::info('=== RETURN INSPECTION END ===');
            
            return redirect()->route('customer.inspections.index')
                ->with('success', 'Return inspection recorded successfully!');
                
        } catch (\Exception $e) {
            Log::error('Return Inspection Error: ' . $e->getMessage());
            Log::error('Error Trace: ' . $e->getTraceAsString());
            
            return redirect()->back()
                ->with('error', 'Error: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Tampilkan borang edit inspection (untuk staff).
     */
    public function edit($id)
    {
        $inspection = Inspection::findOrFail($id);
        return view('inspections.edit', compact('inspection'));
    }

    /**
     * Update inspection record.
     */
    public function update(Request $request, $id)
    {
        $inspection = Inspection::findOrFail($id);
        
        $validated = $request->validate([
            'carCondition'    => 'required|string',
            'mileageReturned' => 'required|numeric',
            'fuelLevel'       => 'required|numeric|min:0|max:100',
            'damageDetected'  => 'required|boolean',
            'remark'          => 'required|string',
        ]);
        
        $inspection->update($validated);
        
        return redirect()->route('inspections.index')
            ->with('success', 'Inspection updated successfully!');
    }
    
    /**
     * Staff: View ALL inspections (including customer submissions)
     */
    /*public function staffIndex()
    {
        // Get ALL inspections, latest first
        $inspections = Inspection::with(['booking', 'vehicle', 'staffUser'])
            ->latest()
            ->paginate(20);
        
        return view('staff.inspections.index', compact('inspections'));
    }*/
    /**
     * Staff: Edit existing inspection (created by customer)
     */
    public function staffEdit($id)
    {
        $inspection = Inspection::with(['booking', 'vehicle'])->findOrFail($id);
        
        return view('staff.inspections.edit', compact('inspection'));
    }

    /**
     * Staff: Update existing inspection
     */
    public function staffUpdate(Request $request, $id)
    {
        $inspection = Inspection::findOrFail($id);
        
        $validated = $request->validate([
            'carCondition'    => 'required|string',
            'mileageReturned' => 'required|integer|min:0',
            'fuelLevel'       => 'required|integer|min:0|max:100',
            'damageDetected'  => 'required|boolean',
            'remark'          => 'required|string',
            'status'          => 'required|in:pending,verified,rejected', // Staff boleh update status
        ]);
        
        // Update inspection
        $inspection->update($validated);
        
        // Log staff action
        \Log::info('Staff updated inspection', [
            'staff_id' => auth()->user()->userID,
            'inspection_id' => $id,
            'changes' => $validated
        ]);
        
        return redirect()->route('staff.inspections.index')
            ->with('success', 'Inspection updated successfully!');
    }
    
    public function staffIndex(Request $request)
    {
        // Start query
        $query = Inspection::with(['booking', 'vehicle']);
        
        // Apply filters
        if ($request->filled('type')) {
            $query->where('inspectionType', $request->type);
        }
        
        if ($request->filled('damage')) {
            if ($request->damage == 'yes') {
                $query->where('damageDetected', true);
            } else {
                $query->where('damageDetected', false);
            }
        }
        
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('bookingID', 'LIKE', "%{$search}%")
                ->orWhere('staffID', 'LIKE', "%{$search}%")
                ->orWhere('vehicleID', 'LIKE', "%{$search}%");
            });
        }
        
        // Get paginated results
        $inspections = $query->latest()->paginate(20);
        
        // Get stats for filters
        $pickupCount = Inspection::where('inspectionType', 'pickup')->count();
        $returnCount = Inspection::where('inspectionType', 'return')->count();
        $damageCount = Inspection::where('damageDetected', true)->count();
        $todayCount = Inspection::whereDate('created_at', now()->format('Y-m-d'))->count();
        $pendingCount = Inspection::where('status', 'pending')->count();
        
        // Condition stats
        $excellentCount = Inspection::where('carCondition', 'excellent')->count();
        $goodCount = Inspection::where('carCondition', 'good')->count();
        $fairCount = Inspection::where('carCondition', 'fair')->count();
        $poorCount = Inspection::where('carCondition', 'poor')->count();
        
        $totalConditions = $excellentCount + $goodCount + $fairCount + $poorCount;
        
        $excellentPercentage = $totalConditions > 0 ? ($excellentCount / $totalConditions) * 100 : 0;
        $goodPercentage = $totalConditions > 0 ? ($goodCount / $totalConditions) * 100 : 0;
        $fairPercentage = $totalConditions > 0 ? ($fairCount / $totalConditions) * 100 : 0;
        $poorPercentage = $totalConditions > 0 ? ($poorCount / $totalConditions) * 100 : 0;
        
        return view('staff.inspections.index', compact(
            'inspections',
            'pickupCount',
            'returnCount',
            'damageCount',
            'todayCount',
            'pendingCount',
            'excellentCount',
            'goodCount',
            'fairCount',
            'poorCount',
            'excellentPercentage',
            'goodPercentage',
            'fairPercentage',
            'poorPercentage'
        ));
    }
}