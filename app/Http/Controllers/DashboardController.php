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
        // 1. High-level metrics
        $newBookings    = DB::table('booking')->whereDate('created_at', now())->count(); // booking baru hari ini

        $rentedCars     = DB::table('booking')->where('bookingStatus', 'booked')->count();      // jumlah kereta sedang disewa

        $availableCars  = DB::table('vehicles')->where('available', 1)->count();         // kereta available

        // 2. Weekly booking bar chart (Mon..Sun) - contoh data statik
        $weeklyLabels = ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'];
        $weeklyData   = [22, 28, 35, 40, 30, 25, 38]; // boleh diganti dengan aggregation dari DB


        // Booking status pie

        // 3. Booking status pie chart

        $statusCancelled = DB::table('booking')->where('bookingStatus', 'cancelled')->count();
        $statusBooked    = DB::table('booking')->where('bookingStatus', 'booked')->count();
        $statusPending   = DB::table('booking')->where('bookingStatus', 'pending')->count();

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
        /*
        $userId = auth()->user()->userId; // ambil ID staff dari login


        // Assigned bookings
        $booking = DB::table('booking')->where('staffID', $userId)->get();

        // KPI cards

        // 1. Semua booking yang assigned pada staff ini
        $booking = DB::table('booking')->where('staffID', $userId)->get();

        // 2. KPI cards

        $assignedToday = DB::table('booking')
            ->where('staffID', $userId)
            ->whereDate('created_at', now())
            ->count(); // booking assigned hari ini

        $pendingPayments = DB::table('payment')
            ->where('staffID', $userId)
            ->where('bookingStatus', 'pending')
            ->count(); // payment pending

        $damageCases = DB::table('damage_case') // sesuaikan table nama plural/singular
            ->where('staffID', $userId)
            ->count();

        // 3. Weekly productivity chart (contoh data)
        $weeklyLabels = ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'];
        $weeklyData   = [3, 6, 5, 7, 4, 2, 8];


        // Status pie (for staff’s assigned bookings)

        // 4. Booking status pie chart untuk staff

        $statusCancelled = DB::table('booking')->where('staffID', $userId)->where('bookingStatus', 'cancelled')->count();
        $statusBooked    = DB::table('booking')->where('staffID', $userId)->where('bookingStatus', 'booked')->count();
        $statusPending   = DB::table('booking')->where('staffID', $userId)->where('bookingStatus', 'pending')->count();

        return view('dashboard.staff', compact(
            'booking',
            'assignedToday',
            'pendingPayments',
            'damageCases',
            'weeklyLabels',
            'weeklyData',
            'statusCancelled',
            'statusBooked',
            'statusPending'
        ));*/
        $user = auth()->user();

if ($user->staffProfile) {
    $staffID = $user->staffProfile->staffID;

    $bookings = DB::table('booking')->where('staffID', $staffID)->get();
    $assignedToday = DB::table('booking')->where('staffID', $staffID)->whereDate('created_at', now())->count();
    $pendingPayments = DB::table('payment')->where('staffID', $staffID)->where('bookingStatus', 'pending')->count();
    $damageCases = DB::table('damage_case')->where('staffID', $staffID)->count();
    $statusCancelled = DB::table('booking')->where('staffID', $staffID)->where('bookingStatus', 'cancelled')->count();
    $statusBooked = DB::table('booking')->where('staffID', $staffID)->where('bookingStatus', 'booked')->count();
    $statusPending = DB::table('booking')->where('staffID', $staffID)->where('bookingStatus', 'pending')->count();
} else {
    $bookings = collect();
    $assignedToday = 0;
    $pendingPayments = 0;
    $damageCases = 0;
    $statusCancelled = 0;
    $statusBooked = 0;
    $statusPending = 0;
}

// Example chart data
$weeklyLabels = ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'];
$weeklyData   = [3,6,5,7,4,2,8];

return view('dashboard.staff', compact(
    'bookings','assignedToday','pendingPayments','damageCases',
    'weeklyLabels','weeklyData','statusCancelled','statusBooked','statusPending'
));

    }

    // ============================
    // Customer Dashboard
    // ============================
    public function customer()
    {

        $userId   = auth()->user()->userID;
        $booking = DB::table('booking')->where('userID', $userId)->get();

        $userId = auth()->user()->userId; // ambil ID customer dari login

        // 1. Ambil semua booking customer
        $booking = DB::table('booking')->where('userID', $userId)->get();

        // 2. Total metrics
        $totalBookings = $booking->count();           // jumlah booking customer
        $totalDays     = $booking->sum('days_rented'); // jumlah hari sewa

        // 3. Most rented car model
        $mostCar = $booking
            ->groupBy('carModel')  // kumpulkan mengikut model
            ->sortByDesc(fn($group) => count($group)) // sort by frequency
            ->keys()                // ambil keys (carModel)
            ->first();              // yang paling tinggi

        // 4. Return view customer
        return view('dashboard.customer', compact(
            'booking', 'totalBookings', 'totalDays', 'mostCar'
        ));
    }
}
