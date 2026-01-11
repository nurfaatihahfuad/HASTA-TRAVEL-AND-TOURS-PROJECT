<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\User;
use App\Models\BlacklistedCust; // Guna model anda
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BlacklistedController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        
        // Query berdasarkan model anda
        $blacklistedQuery = BlacklistedCust::select([
                'users.userID',
                'users.name',
                'users.email',
                'users.noHP',
                'customers.customerID',
                'blacklistedcust.blacklistID',
                'blacklistedcust.reason',
                'admins.name as adminName',
                'admins.adminID',
                DB::raw('(SELECT COUNT(*) FROM bookings WHERE bookings.customerID = customers.customerID) as total_bookings')
            ])
            ->join('customers', 'blacklistedcust.customerID', '=', 'customers.customerID')
            ->join('users', 'customers.userID', '=', 'users.userID')
            ->leftJoin('admins', 'blacklistedcust.adminID', '=', 'admins.adminID')
            ->where('users.status', 'blacklisted'); // Pastikan status match

        // Apply search filter
        if ($search) {
            $blacklistedQuery->where(function($q) use ($search) {
                $q->where('users.name', 'like', "%{$search}%")
                  ->orWhere('users.email', 'like', "%{$search}%")
                  ->orWhere('users.noHP', 'like', "%{$search}%")
                  ->orWhere('blacklistedcust.reason', 'like', "%{$search}%");
            });
        }

        // Paginate results
        $blacklistedCustomers = $blacklistedQuery->orderBy('blacklistedcust.blacklistID', 'desc')
            ->paginate(15)
            ->appends(['search' => $search]);

        // Statistics
        $totalCustomers = Customer::count();
        $totalBlacklisted = BlacklistedCust::count(); // Count dari table blacklistedcust

        $blacklistPercentage = $totalCustomers > 0 
            ? round(($totalBlacklisted / $totalCustomers) * 100, 2)
            : 0;

        return view('admin.customers.blacklisted', compact(
            'blacklistedCustomers',
            'totalBlacklisted',
            'blacklistPercentage',
            'search'
        ));
    }
}