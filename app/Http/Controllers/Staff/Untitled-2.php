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
    // Method untuk filter (optional)
public function staffToday()
{
    $inspections = Inspection::with(['booking.customer.user', 'vehicle'])
        ->whereDate('created_at', today())
        ->latest()
        ->paginate(20);

    return view('staff.inspections.index', compact('inspections'))
        ->with('filter', 'Today');
}

public function staffPending()
{
    $inspections = Inspection::with(['booking.customer.user', 'vehicle'])
        ->where('status', 'pending')
        ->latest()
        ->paginate(20);

    return view('staff.inspections.index', compact('inspections'))
        ->with('filter', 'Pending');
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
                //'damageDetected'  => 'required|in:0,1',
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
                'damageDetected'  => false,
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
     *//*
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
         if ($request->has('has_damage')) {
        // create damage case
        return redirect()
            ->route('inspection.storeReturnInspectionWithDamageCase', $bookingID);
    }
    }*/
 /**
 * Simpan data Return dengan AUTO-CREATE DAMAGE CASE (SINGLE METHOD)
 */

public function storeReturnInspection(Request $request, $id)
{
    try {
        Log::info('=== RETURN INSPECTION WITH DAMAGE CASE START ===');
        Log::info('Booking ID: ' . $id);
        Log::info('User: ' . auth()->user()->userID);
        
        $booking = Booking::with('customer')->findOrFail($id);
        
        // Validate dengan conditional damage fields
        $validated = $request->validate([
            'carCondition'    => 'required|string|min:2',
            'mileageReturned' => 'required|integer|min:0',
            'fuelLevel'       => 'required|integer|min:0|max:100',
            'damageDetected'  => 'required|in:0,1',
            'remark'          => 'required|string|min:3',
            
            // Damage details fields (conditional - hanya jika damageDetected = 1)
            'casetype'     => 'required_if:damageDetected,1',
            'severity'     => 'required_if:damageDetected,1|in:low,medium,high',
        ]);
        
        Log::info('Validated Data: ', $validated);
        
        // Handle file uploads
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
            'staffID'         => auth()->user()->userID,
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
        
        Log::info('Creating inspection with data: ', $inspectionData);
        
        $inspection = Inspection::create($inspectionData);
        Log::info('Inspection Created ID: ' . $inspection->inspectionID);
        /*
        // ⭐⭐⭐ AUTO-CREATE DAMAGE CASE IF DAMAGE DETECTED ⭐⭐⭐
        $hasDamage = $validated['damageDetected'] == '1';
        
        if ($hasDamage) {
            // Handle damage photos
            $damagePhotos = [];
            if ($request->hasFile('damage_photos')) {
                foreach ($request->file('damage_photos') as $photo) {
                    $damagePhotos[] = $photo->store('damage-photos', 'public');
                }
            }
            
            // Generate unique case ID
            $caseID = 'DC' . date('YmdHis') . rand(100, 999);
            
            $damageCase = DamageCase::create([
                'caseID'           => $caseID,
                'inspectionID'     => $inspection->inspectionID,
                'bookingID'        => $booking->bookingID,
                'vehicleID'        => $booking->vehicleID,
                'customerID'       => $booking->userID,
                'reportedBy'       => auth()->user()->name,
                'reportedDate'     => now(),
                'damageType'       => $validated['damage_type'],
                'damageLocation'   => $validated['damage_location'],
                'damageDescription'=> $validated['damage_description'] ?? $validated['remark'],
                'estimatedCost'    => $validated['estimated_cost'] ?? 0,
                'status'           => 'pending',
                'severity'         => $validated['severity'],
                'assignedStaffID'  => null,
                'damage_photos'    => !empty($damagePhotos) ? json_encode($damagePhotos) : null,
            ]);
            
            Log::info('Damage Case Auto-Created: ' . $damageCase->caseID);
            
            // Update booking status untuk damage case
            $booking->update(['status' => 'returned_with_damage']);
            
            Log::info('=== RETURN INSPECTION WITH DAMAGE CASE END ===');
            
            return redirect()->route('customer.inspections.index')
                ->with('warning', 'Return inspection recorded with damage! Damage Case #' . $damageCase->caseID . ' has been created.');
        } else {
            // No damage - normal completion
            $booking->update(['status' => 'completed']);
            
            Log::info('=== RETURN INSPECTION END (NO DAMAGE) ===');
            
            return redirect()->route('customer.inspections.index')
                ->with('success', 'Return inspection recorded successfully! Vehicle returned in good condition.');
        }*/
    // Dalam InspectionController.php - storeReturnInspection method
// Gantikan bahagian ini:
/*
if ($hasDamage) {
    Log::info('Creating Damage Case...');
    
    // Handle damage photos (simpan sebagai JSON jika perlu, tapi column tak wujud)
    // $damagePhotos = []; // Skip dulu kerana column tak wujud
    
    // Generate caseID (MESTI 6 characters MAX!)
    // Format: DC + 2 digit hari + 2 digit random
    $caseID = 'DC' . date('d') . rand(10, 99);
    
    // Data mengikut STRUCTURE DATABASE SEBENAR
    $damageCaseData = [
        'caseID'           => $caseID,                       // varchar(6)
        'useID'           => $booking->userID,              // ❗useID bukan customerID
        'casetype'        => $validated['damage_type'],     // ❗casetype bukan damageType
        'filledby'        => auth()->user()->name,          // ❗filledby bukan reportedBy
        'resolutionstatus'=> 'Unresolved',                  // ❗resolutionstatus bukan status
        'vehicleID'       => $booking->vehicleID,           // int(11)
    ];
    
    Log::info('DamageCase data (MATCHING DB STRUCTURE): ', $damageCaseData);
    
    try {
        // Create damage case dengan data yang MATCH database
        $damageCase = DamageCase::create($damageCaseData);
        Log::info('✅ Damage Case CREATED! ID: ' . $damageCase->caseID);
        
        // Update booking status
        $booking->update(['status' => 'returned_with_damage']);
        Log::info('Booking status updated to returned_with_damage');
        
    } catch (\Exception $e) {
        Log::error('❌ Error creating DamageCase: ' . $e->getMessage());
        Log::error('Error details: ' . $e->getTraceAsString());
        
        // Jika ada error, masih complete inspection tapi tanpa damage case
        $booking->update(['status' => 'completed']);
        Log::warning('Damage case creation failed, but inspection completed');
    }
    
} else {
    Log::info('No damage detected, completing booking normally');
    $booking->update(['status' => 'completed']);
}    */        
    if ($hasDamage) {
    \Log::info('Damage detected, creating damage case...');
    
    // Perhatian: Field dalam form adalah 'casetype' bukan 'damage_type'
    $casetype = $validated['casetype'] ?? 'Collision Damage';
    
    \Log::info('Casetype from form:', ['casetype' => $casetype]);
    
    // Generate case ID (6 characters max!)
    $caseID = 'DC' . date('d') . rand(10, 99);
    
    try {
        // Create damage case dengan field names yang BETUL dari database
        $damageCase = \App\Models\DamageCase::create([
            'caseID' => $caseID,
            'useID' => $booking->userID,
            'casetype' => $casetype, // ❗Field dalam DB: 'casetype'
            'filledby' => auth()->user()->name, // ❗Field dalam DB: 'filledby'
            'resolutionstatus' => 'Unresolved', // ❗Field dalam DB: 'resolutionstatus'
            'vehicleID' => $booking->vehicleID,
        ]);
        
        \Log::info('✅ Damage case created: ' . $damageCase->caseID);
        
        $booking->update(['status' => 'returned_with_damage']);
        
    } catch (\Exception $e) {
        \Log::error('❌ Error creating damage case: ' . $e->getMessage());
        $booking->update(['status' => 'completed']);
    }
} else {
    \Log::info('No damage detected');
    $booking->update(['status' => 'completed']);
}
        
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
     * Staff: Edit existing inspection (created by customer)
     */
    /*public function staffEdit($id)
    {
        $inspection = Inspection::with(['booking', 'vehicle'])->findOrFail($id);
        
        return view('staff.inspections.edit', compact('inspection'));
    }*/

    /**
     * Staff: Update existing inspection
     */
    public function staffUpdate(Request $request, $id)
{
    // Menggunakan DB transaction untuk pastikan kedua-dua table update serentak
    return \DB::transaction(function () use ($request, $id) {
        $inspection = Inspection::with('booking')->findOrFail($id);
        
        $validated = $request->validate([
            'carCondition'    => 'required|string',
            'mileageReturned' => 'required|integer|min:0',
            'fuelLevel'       => 'required|integer|min:0|max:100',
            'damageDetected'  => 'required|boolean',
            'remark'          => 'required|string',
            'status'          => 'required|in:pending,verified,rejected',
            
            // Validasi Damage Case (hanya jika damageDetected = true)
            'damageType'        => 'required_if:damageDetected,1',
            'severity'          => 'required_if:damageDetected,1|in:low,medium,high',
            'resolutionstatus'  => 'required_if:damageDetected,1|in:Unresolved,Resolved',
        ]);

        // 1. Kemaskini table Inspection
        $inspection->update([
            'carCondition'    => $request->carCondition,
            'mileageReturned' => $request->mileageReturned,
            'fuelLevel'       => $request->fuelLevel,
            'damageDetected'  => $request->damageDetected,
            'remark'          => $request->remark,
            'status'          => $request->status,
        ]);

        // 2. Kendalikan Damage Case
        if ($request->damageDetected) {
            // Cari rekod sedia ada atau buat baru guna inspectionID
            $damageCase = DamageCase::updateOrCreate(
                ['inspectionID' => $inspection->inspectionID],
                [
                    'caseID'           => $inspection->damageCase->caseID ?? 'DC' . date('YmdHis'),
                    'bookingID'        => $inspection->bookingID,
                    'vehicleID'        => $inspection->vehicleID,
                    'customerID'       => $inspection->booking->userID,
                    'damageType'       => $request->damageType,
                    'severity'         => $request->severity,
                    'resolutionstatus' => $request->resolutionstatus,
                    'reportedBy'       => auth()->user()->name,
                    'reportedDate'     => now(),
                ]
            );

            // 3. Logic: Jika inspection verified & damage resolved -> Booking Completed
            if ($request->status == 'verified' && $request->resolutionstatus == 'Resolved') {
                $inspection->booking->update(['status' => 'completed']);
            }
        } else {
            // Jika staff tukar dari Damage -> No Damage, hapus rekod kerosakan jika perlu
            // DamageCase::where('inspectionID', $inspection->inspectionID)->delete();
            
            if ($request->status == 'verified') {
                $inspection->booking->update(['status' => 'completed']);
            }
        }

        return redirect()->route('staff.inspections.index')
            ->with('success', 'Inspection and Damage Case updated successfully!');
    });
}
    
    /**
     * Staff: View ALL inspections with filters and statistics
     * UPDATED: Added $totalInspections variable for view
     */
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
        $totalInspections = Inspection::count(); // ← FIXED: Added this line
        $pickupCount = Inspection::where('inspectionType', 'pickup')->count();
        $returnCount = Inspection::where('inspectionType', 'return')->count();
        $damageCount = Inspection::where('damageDetected', true)->count();
        $todayCount = Inspection::whereDate('created_at', now()->format('Y-m-d'))->count();
        
        // Define pendingCount (inspections without remarks)
        $pendingCount = Inspection::whereNull('remark')->count();
        
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
        
        // Log statistics for debugging
        Log::info('Inspection Statistics', [
            'total' => $totalInspections,
            'pickup' => $pickupCount,
            'return' => $returnCount,
            'damage' => $damageCount,
            'today' => $todayCount,
            'pending' => $pendingCount,
        ]);
        
        return view('staff.inspections.index', compact(
            'inspections',
            'totalInspections', // ← FIXED: Now included
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
    /**
 * Staff: Show single inspection details
 */
public function show($id)
{
    $inspection = Inspection::with([
        'vehicle', 
        'booking.user',
        'staffUser'
    ])->findOrFail($id);
    /*
    // Get related data if needed
    $previousInspections = Inspection::where('vehicleID', $inspection->vehicleID)
        ->where('id', '!=', $id)
        ->orderBy('created_at', 'desc')
        ->limit(5)
        ->get();
    */
    return view('staff.inspections.show', compact('inspection'));
}

/**
 * Staff: Show single inspection details (alias untuk staff)
 */
public function staffShow($id)
{
    // You can use the same method or create a different view
    return $this->show($id);
}
/**
 * Show single inspection for customer
 */
public function customerShow($id)
{
    $userID = auth()->user()->userID;
    
    // Ensure customer can only view their own inspection
    $inspection = Inspection::whereHas('booking', function($query) use ($userID) {
            $query->where('userID', $userID);
        })
        ->with(['vehicle', 'booking.user', 'staffUser'])
        ->findOrFail($id);
    
    return view('customer.inspections.show', compact('inspection'));
}
// InspectionController.php - customerIndex() method
// InspectionController.php - REPLACE current customerIndex() method dengan ini:
public function customerIndex()
{
    $userID = auth()->user()->userID;
    
    \Log::info("=== DEBUG CUSTOMER INSPECTIONS ===");
    \Log::info("User ID: " . $userID);
    
    // DEBUG: Check bookings for this user
    $userBookings = Booking::where('userID', $userID)->get();
    \Log::info("Total bookings for user: " . $userBookings->count());
    
    if ($userBookings->count() > 0) {
        \Log::info("Booking IDs: " . $userBookings->pluck('bookingID')->implode(', '));
    }
    
    // Get all inspections for this customer with pagination
    $inspections = Inspection::whereHas('booking', function($query) use ($userID) {
            $query->where('userID', $userID);
        })
        ->with(['vehicle', 'booking'])
        ->orderBy('created_at', 'desc')
        ->paginate(10);
    
    \Log::info("Paginated inspections count: " . $inspections->count());
    \Log::info("Total inspections (paginated total): " . $inspections->total());
    
    // Get customer's recent bookings
    $bookings = Booking::where('userID', $userID)
        ->with(['vehicle', 'inspections'])
        ->orderBy('created_at', 'desc')
        ->limit(5)
        ->get();
    
    // Get statistics - FIXED: Use get() bukan all()
    $allInspections = Inspection::whereHas('booking', function($query) use ($userID) {
            $query->where('userID', $userID);
        })->get();
    
    \Log::info("All inspections (non-paginated) count: " . $allInspections->count());
    
    $totalInspections = $allInspections->count();
    $pickupCount = $allInspections->where('inspectionType', 'pickup')->count();
    $returnCount = $allInspections->where('inspectionType', 'return')->count();
    $damageCount = $allInspections->where('damageDetected', true)->count();
    
    \Log::info("Stats - Total: $totalInspections, Pickup: $pickupCount, Return: $returnCount, Damage: $damageCount");
    
    // Debug: Show each inspection
    foreach ($allInspections as $index => $inspection) {
        \Log::info("Inspection #" . ($index + 1) . 
                  " - ID: " . ($inspection->inspectionID ?? 'N/A') . 
                  ", Type: " . ($inspection->inspectionType ?? 'N/A') . 
                  ", Booking ID: " . ($inspection->bookingID ?? 'N/A') .
                  ", Vehicle ID: " . ($inspection->vehicleID ?? 'N/A'));
    }
    
    return view('customer.inspections.index', compact(
        'inspections', 
        'bookings',
        'totalInspections',
        'pickupCount',
        'returnCount',
        'damageCount'
    ));
}
/**
 * Simpan data Return DENGAN damage detection & auto-create damage case
 */  
public function storeReturnInspectionWithDamageCase(Request $request, $id)
{
    try {
        Log::info('=== RETURN INSPECTION WITH DAMAGE DETECTION START ===');
        
        $booking = Booking::findOrFail($id);
        
        // Validate DENGAN damage details untuk return sahaja
        $validated = $request->validate([
            'carCondition'    => 'required|string|min:2',
            'mileageReturned' => 'required|integer|min:0',
            'fuelLevel'       => 'required|integer|min:0|max:100',
            'damageDetected'  => 'required|in:0,1', // ⭐ HANYA untuk return ⭐
            'remark'          => 'required|string|min:3',
            
            // Damage details fields (conditional - hanya jika damageDetected = 1)
            'damage_type'     => 'required_if:damageDetected,1',
            'damage_location' => 'required_if:damageDetected,1',
            'damage_description' => 'required_if:damageDetected,1',
            'severity'        => 'required_if:damageDetected,1|in:low,medium,high',
            'estimated_cost'  => 'nullable|numeric|min:0',
        ]);
        
        // Handle file uploads
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
        
        // Handle damage photos (hanya jika ada damage)
        $damagePhotos = [];
        $hasDamage = $validated['damageDetected'] == '1';
        
        if ($hasDamage && $request->hasFile('damage_photos')) {
            foreach ($request->file('damage_photos') as $photo) {
                $damagePhotos[] = $photo->store('damage-photos', 'public');
            }
        }
        
        // Create inspection record
        $inspectionData = [
            'bookingID'       => $booking->bookingID,
            'vehicleID'       => $booking->vehicleID,
            'staffID'         => auth()->user()->userID,
            'inspectionType'  => 'return',
            'carCondition'    => $validated['carCondition'],
            'mileageReturned' => (int) $validated['mileageReturned'],
            'fuelLevel'       => (int) $validated['fuelLevel'],
            'damageDetected'  => $hasDamage, // ⭐ Boleh true/false untuk return ⭐
            'remark'          => $validated['remark'],
            'fuel_evidence'   => $fuelEvidence,
            'front_view'      => $frontView,
            'back_view'       => $backView,
            'right_view'      => $rightView,
            'left_view'       => $leftView,
        ];
        
        $inspection = Inspection::create($inspectionData);
        Log::info('Return Inspection Created ID: ' . $inspection->inspectionID);
        
        // ⭐⭐⭐ AUTO-CREATE DAMAGE CASE HANYA JIKA ADA DAMAGE ⭐⭐⭐
        if ($hasDamage) {
            $damageCase = DamageCase::create([
                'caseID'           => 'DC' . date('YmdHis') . rand(100, 999),
                'inspectionID'     => $inspection->inspectionID,
                'bookingID'        => $booking->bookingID,
                'vehicleID'        => $booking->vehicleID,
                'customerID'       => $booking->userID,
                'reportedBy'       => auth()->user()->name,
                'reportedDate'     => now(),
                'damageType'       => $validated['damage_type'],
                'damageLocation'   => $validated['damage_location'],
                'damageDescription'=> $validated['damage_description'] ?? $validated['remark'],
                'estimatedCost'    => $validated['estimated_cost'] ?? 0,
                'status'           => 'pending',
                'severity'         => $validated['severity'],
                'assignedStaffID'  => null,
                'damage_photos'    => !empty($damagePhotos) ? json_encode($damagePhotos) : null,
            ]);
            
            Log::info('Damage Case Auto-Created for Return: ' . $damageCase->caseID);
            
            $booking->update(['status' => 'returned_with_damage']);
            
            Log::info('=== RETURN INSPECTION WITH DAMAGE DETECTION END ===');
            
            return redirect()->route('customer.inspections.index')
                ->with('warning', 'Return inspection recorded with damage! Damage Case #' . $damageCase->caseID . ' has been created.');
        } else {
            // No damage - complete normally
            $booking->update(['status' => 'completed']);
            
            Log::info('=== RETURN INSPECTION WITH DAMAGE DETECTION END ===');
            
            return redirect()->route('customer.inspections.index')
                ->with('success', 'Return inspection recorded successfully! Vehicle returned in good condition.');
        }
        
    } catch (\Exception $e) {
        Log::error('Return Inspection with Damage Detection Error: ' . $e->getMessage());
        
        return redirect()->back()
            ->with('error', 'Error: ' . $e->getMessage())
            ->withInput();
    }
}
// InspectionController.php
public function returnInspectionWithDamage($id)
{
    $booking = Booking::findOrFail($id);
    return view('inspection.return-with-damage', compact('booking'));
}
public function staffEdit($id)
{
    $inspection = Inspection::with(['booking', 'vehicle', 'damageCase'])->findOrFail($id);
    
    // Ambil senarai user yang mempunyai role 'staff' untuk ditugaskan
    $staffList = \App\Models\User::where('role', 'staff')->get();
    
    return view('staff.inspections.edit', compact('inspection', 'staffList'));
}
}