<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller as BaseController;
use Illuminate\Http\Request;
use App\Models\DamageCase;
use App\Models\User;
use App\Models\Inspection;
use Illuminate\Support\Facades\Storage;

class DamageCaseController extends BaseController
{
    /**
     * Display listing of damage cases
     */
    public function index()
    {
        $damageCases = DamageCase::with(['inspection', 'user'])
            ->orderBy('caseID', 'desc')
            ->paginate(20);
        
        return view('staff.damage_case.index', compact('damageCases'));
    }

    /**
     * Show the form for creating a new damage case
     */
    public function create()
    {
        // Dapatkan SEMUA inspections (tanpa filter damage_detected)
        $inspections = Inspection::with('vehicle')
            ->latest()
            ->get();

        $staffUsers = User::where('userType', 'staff')->get();

        return view('staff.damage_case.create', compact('inspections', 'staffUsers'));
    }

    /**
     * Store a newly created damage case
     */
    /*
    public function store(Request $request)
    {
        $request->validate([
            'inspectionID' => 'nullable|exists:inspection,inspectionID',
            'casetype' => 'required|in:Collision Damage,Non-Collision Damage',
            'severity' => 'required|in:Low - Minor cosmetic,Medium - Requires repair,High - Major damage',
            'filledby' => 'required|string|max:50',
            'resolutionstatus' => 'required|in:Open,In Progress,Resolved,Closed,Cancelled',
            'damage_photos' => 'nullable|array',
            'damage_photos.*' => 'image|max:2048',
        ]);

        // Debug: Check table columns
        \Log::info('Creating damage case for user: ' . auth()->id());
        
        // Handle photos
        $photoFilenames = [];
        if ($request->hasFile('damage_photos')) {
            foreach ($request->file('damage_photos') as $photo) {
                $filename = time() . '_' . $photo->getClientOriginalName();
                $photo->storeAs('public/damage-photos', $filename);
                $photoFilenames[] = $filename;
            }
        }

        try {
            // Create damage case - GUNA COLUMN YANG BETUL
            $damageData = [
                'casetype' => $request->casetype,
                'severity' => $request->severity,
                'damage_photos' => !empty($photoFilenames) ? json_encode($photoFilenames) : '',
                'filledby' => $request->filledby,
                'resolutionstatus' => $request->resolutionstatus,
                'inspectionID' => $request->inspectionID,
            ];

            // Check jika userID column wujud
            if (\Schema::hasColumn('damage_case', 'userID')) {
                $damageData['userID'] = auth()->id();
            }
            // Atau jika ada column lain seperti 'created_by'
            elseif (\Schema::hasColumn('damage_case', 'created_by')) {
                $damageData['created_by'] = auth()->id();
            }

            DamageCase::create($damageData);

            return redirect()->route('staff.damage-cases.index')
                ->with('success', 'Damage case created successfully.');
                
        } catch (\Exception $e) {
            \Log::error('Error creating damage case: ' . $e->getMessage());
            
            return back()->withInput()->with('error', 'Failed to create damage case: ' . $e->getMessage());
        }
    }*/
        public function store(Request $request)
{
    // Debug: Log request data
    \Log::info('Store request data:', $request->all());
    
    $validator = \Validator::make($request->all(), [
        'inspectionID' => 'nullable|exists:inspection,inspectionID',
        'casetype' => 'required|in:Collision Damage,Non-Collision Damage',
        'severity' => 'required|in:Low - Minor cosmetic,Medium - Requires repair,High - Major damage',
        'filledby' => 'required|string|max:50',
        'resolutionstatus' => 'required|in:Open,In Progress,Resolved,Closed,Cancelled',
        'damage_photos' => 'nullable|array',
        'damage_photos.*' => 'image|max:2048',
    ]);

    if ($validator->fails()) {
        \Log::error('Validation failed:', $validator->errors()->toArray());
        return back()->withErrors($validator)->withInput();
    }

    // Handle photos
    $photoFilenames = [];
    if ($request->hasFile('damage_photos')) {
        foreach ($request->file('damage_photos') as $photo) {
            $filename = time() . '_' . $photo->getClientOriginalName();
            $photo->storeAs('public/damage-photos', $filename);
            $photoFilenames[] = $filename;
        }
    }

    try {
        // Debug: Check sebelum create
        \Log::info('Creating damage case with data:', [
            'casetype' => $request->casetype,
            'severity' => $request->severity,
            'filledby' => $request->filledby,
            'resolutionstatus' => $request->resolutionstatus,
            'inspectionID' => $request->inspectionID,
        ]);

        // Create damage case TANPA userID
        $damageCase = DamageCase::create([
            'casetype' => $request->casetype,
            'severity' => $request->severity,
            'damage_photos' => !empty($photoFilenames) ? json_encode($photoFilenames) : '',
            'filledby' => $request->filledby,
            'resolutionstatus' => $request->resolutionstatus,
            'inspectionID' => $request->inspectionID,
        ]);

        \Log::info('Damage case created successfully. ID: ' . $damageCase->caseID);

        return redirect()->route('staff.damage-cases.index')
            ->with('success', 'Damage case #' . $damageCase->caseID . ' created successfully.');
            
    } catch (\Exception $e) {
        \Log::error('Error creating damage case: ' . $e->getMessage());
        \Log::error('Stack trace: ' . $e->getTraceAsString());
        
        return back()->withInput()->with('error', 'Failed to create damage case: ' . $e->getMessage());
    }
}

    /**
     * Display the specified damage case
     */
    public function show($id)
    {
        $damageCase = DamageCase::with(['inspection.vehicle', 'user'])
            ->findOrFail($id);

        return view('staff.damage_case.show', compact('damageCase'));
    }

    /**
     * Show the form for editing the specified damage case
     */
    public function edit($id)
    {
        $damageCase = DamageCase::findOrFail($id);
        $staffUsers = User::where('userType', 'staff')->get();

        return view('staff.damage_case.edit', compact('damageCase', 'staffUsers'));
    }

    /**
     * Update the specified damage case
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'casetype' => 'required|in:Collision Damage,Non-Collision Damage',
            'severity' => 'required|in:Low - Minor cosmetic,Medium - Requires repair,High - Major damage',
            'filledby' => 'required|string|max:50',
            'resolutionstatus' => 'required|in:Open,In Progress,Resolved,Closed,Cancelled',
        ]);

        $damageCase = DamageCase::findOrFail($id);
        $damageCase->update($request->only([
            'casetype', 'severity', 'filledby', 'resolutionstatus'
        ]));

        return redirect()->route('staff.damage-cases.show', $damageCase)
            ->with('success', 'Damage case updated successfully.');
    }
}