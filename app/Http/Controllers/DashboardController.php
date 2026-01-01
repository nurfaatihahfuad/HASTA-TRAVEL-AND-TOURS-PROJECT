<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    // Admin dashboard
    public function admin()
    {
        // High-level metrics
        $newBookings = DB::table('bookings')->whereDate('created_at', now())->count();
        $rentedCars  = DB::table('bookings')->where('status', 'booked')->count();
        $availableCars = DB::table('vehicles')->where('available', 1)->count();


        // Weekly booking bar chart (Mon..Sun)
        $weeklyLabels = ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'];
        $weeklyData   = [22, 28, 35, 40, 30, 25, 38]; // Replace with real aggregation if needed

        // Booking status pie
        $statusCancelled = DB::table('bookings')->where('status', 'cancelled')->count();
        $statusBooked    = DB::table('bookings')->where('status', 'booked')->count();
        $statusPending   = DB::table('bookings')->where('status', 'pending')->count();

        // Car type distribution (example categories)
        $carTypes = [
            ['label' => 'Proton Axia', 'value' => 75],
            ['label' => 'Sedan', 'value' => 60],
            ['label' => 'Bezza', 'value' => 30],
        ];

        return view('dashboard.admin', compact(
            'newBookings',
            'rentedCars',
            'availableCars',
            'weeklyLabels',
            'weeklyData',
            'statusCancelled',
            'statusBooked',
            'statusPending',
            'carTypes'
        ));
    }

    // Staff dashboard
    public function staff()
    {
        $userId = auth()->user()->userID;

        // Assigned bookings
        $bookings = DB::table('bookings')->where('staffID', $userId)->get();

        // KPI cards
        $assignedToday = DB::table('bookings')
            ->where('staffID', $userId)
            ->whereDate('created_at', now())
            ->count();

        $pendingPayments = DB::table('payment')
            ->where('staffID', $userId)
            ->where('status', 'pending')
            ->count();

        $damageCases = DB::table('damage_case') // tukar ke 'damage_cases' kalau DB awak plural
            ->where('staffID', $userId)
            ->count();

        // Weekly productivity chart
        $weeklyLabels = ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'];
        $weeklyData   = [3, 6, 5, 7, 4, 2, 8]; // Replace with real aggregation if needed

        // Status pie (for staff’s assigned bookings)
        $statusCancelled = DB::table('bookings')->where('staffID', $userId)->where('status', 'cancelled')->count();
        $statusBooked    = DB::table('bookings')->where('staffID', $userId)->where('status', 'booked')->count();
        $statusPending   = DB::table('bookings')->where('staffID', $userId)->where('status', 'pending')->count();

        return view('dashboard.staff', compact(
            'bookings',
            'assignedToday',
            'pendingPayments',
            'damageCases',
            'weeklyLabels',
            'weeklyData',
            'statusCancelled',
            'statusBooked',
            'statusPending'
        ));
    }

    // Customer dashboard
    public function customer()
    {
        $userId   = auth()->user()->userID;
        $bookings = DB::table('bookings')->where('userID', $userId)->get();

        $totalBookings = $bookings->count();
        $totalDays     = $bookings->sum('days_rented');

        // Betulkan logic mostCar
        $mostCar = $bookings
            ->groupBy('carModel')
            ->sortByDesc(function ($group) {
                return count($group);
            })
            ->keys()
            ->first();

        return view('dashboard.customer', compact('bookings', 'totalBookings', 'totalDays', 'mostCar'));
    }
}
