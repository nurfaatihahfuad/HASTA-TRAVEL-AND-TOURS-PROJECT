<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class StaffController extends Controller
{
    
    // Display a listing of staff members.
    public function index(Request $request)
    {
        // Get total counts before filtering
        $totalStaff = User::where('userType', 'staff')->count();
        $totalSalespersons = User::where('userType','staff')
                            ->whereHas('staff', function($q) {
                                $q->where('staffRole','salesperson');
                            })->count();
        $totalRunners = User::where('userType','staff')
                            ->whereHas('staff', function($q) {
                                $q->where('staffRole','runner');
                            })->count();
        // Get all staff (userType = 'staff')
        // order by latest staff added
        /*$staff = User::where('userType', 'staff')
                    ->orderBy('created_at', 'desc')
                    ->get();*/

        // Get all staff with their staff role information
        $query = User::where('userType', 'staff')
                    ->with('staff') // Eager load the staff relationship
                    ->orderBy('name');

        // Apply role filter if specified
        if ($request->has('role') && in_array($request->role, ['salesperson', 'runner'])) {
            $query->whereHas('staff', function($q) use ($request) {
                $q->where('staffRole', $request->role);
            });
        }
    
        $staff = $query->get();
        
        return view('admin.staff.index',[
            'staff' => $staff,
            'totalStaff' => $totalStaff,
            'totalSalespersons' => $totalSalespersons,
            'totalRunners' => $totalRunners,
            'currentFilter' => $request->role
        ]);
    }

    // Show the form for creating a new staff member.
    
    public function create()
    {
        return view('admin.staff.create');
    }

    // Store a newly created staff member in storage.
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:user,email',
            'password' => 'required|min:8|confirmed',
            'noHP' => 'required|string|max:15',
            'noIC' => 'required|string|max:12|unique:user,noIC',
            'staffRole' => 'required|in:salesperson,runner',
        ]);

        try {
            DB::beginTransaction();

            // Create user first
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'noHP' => $validated['noHP'],
                'noIC' => $validated['noIC'],
                'userType' => 'staff',
            ]);

            // Debug: Check if user was created
            Log::info('User created successfully', ['userID' => $user->userID]);
            
            // Then explicitly create staff record with the selected role
            Staff::create([
                'staffID' => $user->userID,
                'staffRole' => $validated['staffRole']
            ]);
            
            Log::info('Staff created by admin', [
                'admin_id' => auth()->id(),
                'staff_id' => $user->userID,
                'staff_role' => $validated['staffRole'],
                'email' => $validated['email']
            ]);


            DB::commit();

            return redirect()->route('staff.index')
                ->with('success', 'Staff member created successfully! Staff ID: ' . $user->userID);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create staff', [
                'error' => $e->getMessage(),
                'data' => $validated
            ]);
            
            return back()->withErrors(['error' => 'Failed to create staff. Please try again.'])
                ->withInput();
        }
    }

    // Display the specified staff member.
    public function show($id)
    {
        $staff = User::where('userID', $id)
                    ->where('userType', 'staff')
                    ->firstOrFail(); // laravel built-in function to throw error code if NULL
        
        return view('admin.staff.show', compact('staff'));
    }

    // Show the form for editing the specified staff member.
    public function edit($id)
    {
        $staff = User::where('userID', $id)
                    ->where('userType', 'staff')
                    ->firstOrFail();
        
        return view('admin.staff.update', compact('staff'));
    }

    // Update the specified staff member in storage (submit update form)
    public function update(Request $request, $id)
    {
        $staff = User::where('userID', $id)
                    ->where('userType', 'staff')
                    ->with('staff')
                    ->firstOrFail();
        
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:user,email,' . $staff->userID . ',userID',
            'noHP' => 'required|string|max:15',
            'noIC' => 'required|string|max:12|unique:user,noIC,' . $staff->userID . ',userID',
            'staffRole' => 'required|in:salesperson,runner',
        ]);

        // Update staff
        /*$staff->update($validated);

        return redirect()->route('staff.index')
            ->with('success', 'Staff member updated successfully!');*/
        try {
            DB::beginTransaction();

            // Update user details
            $staff->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'noHP' => $validated['noHP'],
                'noIC' => $validated['noIC'],
            ]);

            // Update or create staff record
            if ($staff->staff) {
                $staff->staff->update([
                    'staffRole' => $validated['staffRole']
                ]);
            } else {
                Staff::create([
                    'staffID' => $staff->userID,
                    'staffRole' => $validated['staffRole']
                ]);
            }
            DB::commit();

            Log::info('Staff updated by admin', [
                'admin_id' => auth()->id(),
                'staff_id' => $staff->userID,
                'new_role' => $validated['staffRole']
            ]);

            return redirect()->route('staff.index')
                ->with('success', 'Staff member updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Failed to update staff', [
                'error' => $e->getMessage(),
                'staff_id' => $id,
                'data' => $validated
            ]);
            
            return back()->withErrors(['error' => 'Failed to update staff. Please try again.'])
                ->withInput();
        }
    }

    // Remove the specified staff member from storage.
    public function destroy($id)
    {
        $staff = User::where('userID', $id)
                    ->where('userType', 'staff')
                    ->firstOrFail();
        
        // Prevent deleting yourself if you're also staff
        if ($staff->userID === auth()->id()) {
            return redirect()->route('staff.index')
                ->with('error', 'You cannot delete your own account!');
        }
        
        $staff->delete();

        Log::info('Staff deleted by admin', [
            'admin_id' => auth()->id(),
            'deleted_staff_id' => $id
        ]);

        return redirect()->route('staff.index')
            ->with('success', 'Staff member deleted successfully!');
    }

}