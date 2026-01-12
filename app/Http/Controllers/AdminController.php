<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Admin;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminController extends Controller
{

    // Display a listing of admins
    public function index(Request $request)
    {
        // Get total counts before filtering
        $totalAdmin = User::where('userType', 'admin')->count();
        $totalITAdmin = User::where('userType','admin')
                            ->whereHas('admin', function($q) {
                                $q->where('adminType','IT');
                            })->count();
        $totalfinanceAd = User::where('userType','admin')
                            ->whereHas('admin', function($q) {
                                $q->where('adminType','finance');
                            })->count();

        // Debug: Check what's happening
        \Log::info('Admin index loading...');
        
        $query = User::where('userType', 'admin')
                      ->with(['admin' => function($query) {
                            \Log::info('Eager loading admin relationship');
                            return $query;
                        }])
                        ->orderBy('name');

        // Apply role filter
        if ($request->has('role') && in_array($request->role, ['finance', 'IT'])) {
            $query->whereHas('admin', function($q) use ($request) {
                $q->where('adminType', $request->role);
            });
        }
        $admin = $query->get();

        // Debug: Check what we loaded
        \Log::info('Loaded ' . $admin->count() . ' admin users');
        foreach ($admin as $user) {
            \Log::info('User ' . $user->userID . ':', [
                'name' => $user->name,
                'has_admin_relation' => !is_null($user->admin),
                'admin_type' => $user->admin ? $user->admin->adminType : 'NO RELATION'
            ]);
        }
        
        return view('admin.admins.index', [
            'admin' => $admin,
            'totalAdmin' => $totalAdmin,
            'totalITAdmin' => $totalITAdmin,
            'totalfinanceAd' => $totalfinanceAd,
            'currentFilter' => $request->role
        ]);
    }

    // Show the form for creating a new admin
    public function create()
    {
        return view('admin.admins.create'); //path: resources/views/admin/admins/create
    }

    // Store a newly created admin
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:user,email',
            'password' => 'required|min:8|confirmed',
            'noHP' => 'required|string|max:15',
            'noIC' => 'required|string|max:12|unique:user,noIC',
            'adminType' => 'required|in:finance,IT',
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
                'userType' => 'admin',
            ]);

            // Debug: Check if user was created
            Log::info('User created successfully', ['userID' => $user->userID]);
            
            
            Admin::create([
                'adminID' => $user->userID,
                'adminType' => $validated['adminType'],
                'is_active' => true,
            ]);

            Log::info('New Admin created', [
                'creator_id' => auth()->id(),
                'adminID' => $user->userID,
                'adminType' => $validated['adminType'],
                'email' => $validated['email']
            ]);

            DB::commit();

            return redirect()->route('admins.index')
                ->with('success', 'Admin created successfully! Admin ID: ' . $user->userID);

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Failed to create admin', [
                'error' => $e->getMessage(),
                'data' => $validated
            ]);
            
            return back()->withErrors(['error' => 'Failed to create admin. Please try again.'])
                ->withInput();
        }
    }

    // Display the specified admin
    public function show($id)
    {
        $admins = User::where('userID', $id)
                    ->where('userType', 'admin')
                    ->firstOrFail();
        
        return view('admin.admins.show', compact('admins'));
    }

    // Show the form for editing the admin
    public function edit($id)
    {
        $admins = User::where('userID', $id)
                    ->where('userType', 'admin')
                    ->firstOrFail();
        
        return view('admin.admins.update', compact('admins'));
    }

    // Update the specified admin (submit update form)
    public function update(Request $request, $id)
    {
        $admins = User::where('userID', $id)
                    ->where('userType', 'admin')
                    ->with('admin')
                    ->firstOrFail();

        // CRITICAL: Check if admin record exists
        if (!$admins->admin) {
            \Log::error('NO ADMIN RECORD FOUND IN DATABASE FOR USER!', [
                'userID' => $admins->userID,
                'userName' => $admins->name
            ]);

        } else {
            \Log::info('Admin record loaded:', [
                'adminID' => $admins->admin->adminID,
                'adminType' => $admins->admin->adminType,
                'is_active' => $admins->admin->is_active
            ]);
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:user,email,' . $admins->userID . ',userID',
            'noHP' => 'required|string|max:15',
            'noIC' => 'required|string|max:12|unique:user,noIC,' . $admins->userID . ',userID',
            'adminType' => 'required|in:finance,IT',
        ]);


        try {
            DB::beginTransaction();

            // Update user record
            $userUpdateData = [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'noHP' => $validated['noHP'],
                'noIC' => $validated['noIC'],
            ];
            $userUpdated = $admins->update($userUpdateData);

            // 2. Handle Admin record
            $adminUpdated = false;
            $adminCreated = false;

            // Check again if admin record exists (after user update)
            $adminRecord = Admin::where('adminID', $admins->userID)->first();
            
            if ($adminRecord) {
                // Save current state
                $oldAdminType = $adminRecord->adminType;
                // Method 1: Direct update
                $adminRecord->adminType = $validated['adminType'];

                // Verify the update
                $adminRecord->refresh();

            } else {
                // Create new admin record
                $newAdmin = Admin::create([
                    'adminID' => $admins->userID,
                    'adminType' => $validated['adminType'],
                    'is_active' => true,
                ]);
                
                $adminCreated = true;
            }

            DB::commit();

            return redirect()->route('admins.index')
                ->with('success', 'Admin updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Failed to update admin', [
                'error' => $e->getMessage(),
                'admin_id' => $id,
                'data' => $validated
            ]);
            
            return back()->withErrors(['error' => 'Failed to update admin. Please try again.'])
                ->withInput();
        }
    }

    // Remove the specified admin
    public function destroy($id)
    {
        $admins = User::where('userID', $id)
                    ->where('userType', 'admin')
                    ->firstOrFail();
        
        // Prevent deleting yourself
        if ($admins->userID === auth()->id()) {
            return redirect()->route('admins.index')
                ->with('error', 'You cannot delete your own account!');
        }
        
        try {
            DB::beginTransaction();
            
            $admins->delete();

            DB::commit();

            Log::info('Admin deleted', [
                'deleter_id' => auth()->id(),
                'deleted_admin_id' => $id
            ]);

            return redirect()->route('admins.index')
                ->with('success', 'Admin deleted successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Failed to delete admin', [
                'error' => $e->getMessage(),
                'admin_id' => $id
            ]);
            
            return redirect()->route('admins.index')
                ->with('error', 'Failed to delete admin. Please try again.');
        }
    }

    // Change admin password
    /*public function changePassword(Request $request, $id)
    {
        $admin = User::where('userID', $id)
                    ->where('userType', 'admin')
                    ->firstOrFail();
        
        $validated = $request->validate([
            'password' => 'required|min:8|confirmed',
        ]);

        $admin->update([
            'password' => Hash::make($validated['password'])
        ]);

        Log::info('Admin password changed', [
            'changer_id' => auth()->id(),
            'admin_id' => $admin->userID
        ]);

        return back()->with('success', 'Password changed successfully!');
    }*/

    // View Booking
    public function bookingIndex(Request $request)
    {
        $query = Booking::with(['user', 'vehicle']);

        // 🔍 Filter by status
        if ($request->filled('status')) {
            $query->where('bookingStatus', $request->status);
        }

        // 📅 Filter by date range (pickup)
        if ($request->filled('from')) {
            $query->whereDate('pickup_dateTime', '>=', $request->from);
        }

        if ($request->filled('to')) {
            $query->whereDate('pickup_dateTime', '<=', $request->to);
        }

        $bookings = $query
            ->orderBy('pickup_dateTime', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('admin.bookings.index', compact('bookings'));
    }

    public function bookingShow(Booking $booking)
    {
        $booking->load(['user', 'vehicle', 'payments', 'inspections']);

        return view('admin.bookings.show', compact('booking'));
    }

    // Check vehicle availability in dashboard
    public function checkAvailability(Request $request)
    {
        
        $request->validate([
            'vehicleID' => 'required',
            'pickup_dateTime' => 'required|date',
            'return_dateTime' => 'required|date|after:pickup_dateTime',
        ]);

        $pickup = Carbon::createFromFormat('Y-m-d\TH:i', $request->pickup_dateTime);
        $return = Carbon::createFromFormat('Y-m-d\TH:i', $request->return_dateTime);
        $conflict = Booking::where('vehicleID', $request->vehicleID)
            ->whereIn('bookingStatus', ['successful'])
            ->where(function ($query) use ($pickup, $return) {
                $query->whereBetween('pickup_dateTime', [$pickup, $return])
                    ->orWhereBetween('return_dateTime', [$pickup, $return])
                    ->orWhere(function ($q) use ($pickup, $return) {
                        $q->where('pickup_dateTime', '<=', $pickup)
                            ->where('return_dateTime', '>=', $return);
                    });
            })
            ->exists();

        return back()->with(
            $conflict ? 'error' : 'success',
            $conflict
                ? 'Vehicle is NOT available for the selected time.'
                : 'Vehicle is available ✅'
        );

        
    }

}