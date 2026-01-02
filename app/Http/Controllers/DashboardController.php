<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    // ============================
    // Admin Dashboard
    // ============================
    public function admin()
    {
<<<<<<< Updated upstream
        // High-level metrics
        $newBookings = DB::table('booking')->whereDate('created_at', now())->count();
        $rentedCars  = DB::table('booking')->where('status', 'booked')->count();
        $availableCars = DB::table('vehicle')->where('available', 1)->count();
=======
        // 1. High-level metrics
        $newBookings    = DB::table('booking')->whereDate('created_at', now())->count(); // booking baru hari ini
        $rentedCars     = DB::table('booking')->where('status', 'booked')->count();      // jumlah kereta sedang disewa
        $availableCars  = DB::table('vehicles')->where('available', 1)->count();         // kereta available
>>>>>>> Stashed changes

        // 2. Weekly booking bar chart (Mon..Sun) - contoh data statik
        $weeklyLabels = ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'];
        $weeklyData   = [22, 28, 35, 40, 30, 25, 38]; // boleh diganti dengan aggregation dari DB

<<<<<<< Updated upstream
        // Booking status pie
=======
        // 3. Booking status pie chart
>>>>>>> Stashed changes
        $statusCancelled = DB::table('booking')->where('status', 'cancelled')->count();
        $statusBooked    = DB::table('booking')->where('status', 'booked')->count();
        $statusPending   = DB::table('booking')->where('status', 'pending')->count();

        // 4. Car type distribution (example)
        $carTypes = [
            ['label' => 'Proton Axia', 'value' => 75],
            ['label' => 'Sedan', 'value' => 60],
            ['label' => 'Bezza', 'value' => 30],
        ];

        // Return view admin dengan semua data
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

    // ============================
    // Staff Dashboard
    // ============================
    public function staff()
    {
        $userId = auth()->user()->userId; // ambil ID staff dari login

<<<<<<< Updated upstream
        // Assigned bookings
        $bookings = DB::table('booking')->where('staffID', $userId)->get();

        // KPI cards
=======
        // 1. Semua booking yang assigned pada staff ini
        $bookings = DB::table('booking')->where('staffID', $userId)->get();

        // 2. KPI cards
>>>>>>> Stashed changes
        $assignedToday = DB::table('booking')
            ->where('staffID', $userId)
            ->whereDate('created_at', now())
            ->count(); // booking assigned hari ini

        $pendingPayments = DB::table('payment')
            ->where('staffID', $userId)
            ->where('status', 'pending')
            ->count(); // payment pending

        $damageCases = DB::table('damage_case') // sesuaikan table nama plural/singular
            ->where('staffID', $userId)
            ->count();

        // 3. Weekly productivity chart (contoh data)
        $weeklyLabels = ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'];
        $weeklyData   = [3, 6, 5, 7, 4, 2, 8];

<<<<<<< Updated upstream
        // Status pie (for staff’s assigned bookings)
=======
        // 4. Booking status pie chart untuk staff
>>>>>>> Stashed changes
        $statusCancelled = DB::table('booking')->where('staffID', $userId)->where('status', 'cancelled')->count();
        $statusBooked    = DB::table('booking')->where('staffID', $userId)->where('status', 'booked')->count();
        $statusPending   = DB::table('booking')->where('staffID', $userId)->where('status', 'pending')->count();

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

    // ============================
    // Customer Dashboard
    // ============================
    public function customer()
    {
<<<<<<< Updated upstream
        $userId   = auth()->user()->userID;
        $bookings = DB::table('booking')->where('userID', $userId)->get();
=======
        $userId = auth()->user()->userId; // ambil ID customer dari login
>>>>>>> Stashed changes

        // 1. Ambil semua booking customer
        $bookings = DB::table('booking')->where('userID', $userId)->get();

        // 2. Total metrics
        $totalBookings = $bookings->count();           // jumlah booking customer
        $totalDays     = $bookings->sum('days_rented'); // jumlah hari sewa

        // 3. Most rented car model
        $mostCar = $bookings
            ->groupBy('carModel')  // kumpulkan mengikut model
            ->sortByDesc(fn($group) => count($group)) // sort by frequency
            ->keys()                // ambil keys (carModel)
            ->first();              // yang paling tinggi

        // 4. Return view customer
        return view('dashboard.customer', compact(
            'bookings', 'totalBookings', 'totalDays', 'mostCar'
        ));
    }
}
