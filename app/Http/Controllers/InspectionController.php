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
public function adminShow($id)
{
    // 1. Cari data inspection berdasarkan ID
    $inspection = Inspection::with(['booking', 'vehicle', 'staffUser'])->findOrFail($id);

    // 2. PASTI KAN DI SINI : admin.inspections.show
    // Bukan staff.inspections.show
    return view('admin.inspections.show', compact('inspection'));
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
 * Admin: View ALL inspections from all users and staff
 */
public function adminIndex(Request $request)
{
    $query = Inspection::with(['booking', 'vehicle', 'staffUser']);
    
    // Filter berdasarkan input admin
    if ($request->filled('type')) {
        $query->where('inspectionType', $request->type);
    }
    
    if ($request->filled('damage')) {
        $query->where('damageDetected', $request->damage == 'yes');
    }
    
    if ($request->filled('date')) {
        $query->whereDate('created_at', $request->date);
    }
    
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->where('bookingID', 'LIKE', "%{$search}%")
              ->orWhere('staffID', 'LIKE', "%{$search}%")
              ->orWhereHas('vehicle', function($v) use ($search) {
                  $v->where('vehicleName', 'LIKE', "%{$search}%")
                    ->orWhere('plateNo', 'LIKE', "%{$search}%");
              });
        });
    }

    $inspections = $query->latest()->paginate(15);

    // Statistik Global untuk Admin
    $totalInspections = Inspection::count();
    $pickupCount = Inspection::where('inspectionType', 'pickup')->count();
    $returnCount = Inspection::where('inspectionType', 'return')->count();
    $damageCount = Inspection::where('damageDetected', true)->count();

    return view('admin.inspections.index', compact(
        'inspections',
        'totalInspections',
        'pickupCount',
        'returnCount',
        'damageCount'
    ));
}
}