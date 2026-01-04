<?php

/* 
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
        $user = auth()->user();
        if (!$user->staff) {
            // fallback if no staff record
            return view('dashboard.staff', [
                'role'            => null,
                'bookings'        => collect(),
                'assignedToday'   => 0,
                'pendingPayments' => 0,
                'damageCases'     => 0,
                'statusCancelled' => 0,
                'statusBooked'    => 0,
                'statusPending'   => 0,
                'weeklyLabels'    => ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'],
                'weeklyData'      => [0,0,0,0,0,0,0],
            ]);
        }

        $staffID = $user->staff->staffID;
        $role    = $user->staff->staffRole;

        $weeklyLabels = ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'];
        $weeklyData   = [3,6,5,7,4,2,8]; // should calculate booking count for every day in current week

        if($user->isSalesperson()) {
            // Salesperson metrics
            $bookings        = DB::table('booking')->get();
            //$assignedToday   = DB::table('booking')->where('staffID', $staffID)->whereDate('created_at', now())->count();
            //$pendingPayments = DB::table('payment')->where('staffID', $staffID)->where('bookingStatus', 'pending')->count();

            $statusCancelled = DB::table('booking')->where('bookingStatus', 'cancelled')->count();
            $statusBooked    = DB::table('booking')->where('bookingStatus', 'booked')->count();
            $statusPending   = DB::table('booking')->where('bookingStatus', 'pending')->count();

            return view('dashboard.staff_salesperson', compact(
                'role','bookings','statusCancelled','statusBooked','statusPending',
                'weeklyLabels','weeklyData'
            ));
        }

        if ($user->isRunner()) {
            // Runner metrics
            $bookings      = DB::table('booking')->where('staffID', $staffID)->get();
            $assignedToday = DB::table('booking')->where('staffID', $staffID)->whereDate('created_at', now())->count();
            $damageCases   = DB::table('damage_case')->where('staffID', $staffID)->count();

            $statusCancelled = DB::table('booking')->where('staffID', $staffID)->where('bookingStatus', 'cancelled')->count();
            $statusBooked    = DB::table('booking')->where('staffID', $staffID)->where('bookingStatus', 'booked')->count();
            $statusPending   = DB::table('booking')->where('staffID', $staffID)->where('bookingStatus', 'pending')->count();

            return view('dashboard.staff_runner', compact(
                'role','bookings','assignedToday','damageCases',
                'statusCancelled','statusBooked','statusPending',
                'weeklyLabels','weeklyData'
            ));
        }

        // fallback if role not recognized
        return view('dashboard.staff', [
            'role'            => $role,
            'bookings'        => collect(),
            'assignedToday'   => 0,
            'pendingPayments' => 0,
            'damageCases'     => 0,
            'statusCancelled' => 0,
            'statusBooked'    => 0,
            'statusPending'   => 0,
            'weeklyLabels'    => $weeklyLabels,
            'weeklyData'      => $weeklyData,
        ]);

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

*/


namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    // ============================
    // Admin IT Dashboard
    // ============================
    public function adminIT()
    {
        // System overview
        $totalUsers    = DB::table('user')->count();
        $totalStaff    = DB::table('staff')->count();
        $totalVehicles = DB::table('vehicles')->count();

        // Booking metrics
        $newBookings   = DB::table('booking')->where('bookingStatus','new')->count();
        $rentedCars    = DB::table('booking')->where('bookingStatus','rented')->count();
        $availableCars = DB::table('vehicles')->where('available',1)->count();

        // Weekly booking overview (group by day of week)
        $weeklyLabels = ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'];
        $weeklyData   = [];
        foreach ($weeklyLabels as $day) {
            $weeklyData[] = DB::table('booking')
                ->whereRaw('DAYNAME(created_at) = ?', [$day])
                ->count();
        }

        // Car types distribution (group by vehicleName)
        $carTypes = DB::table('vehicles')
            ->select('vehicleName', DB::raw('COUNT(*) as total'))
            ->groupBy('vehicleName')
            ->get()
            ->map(function($row) use ($totalVehicles) {
                return [
                    'label' => $row->vehicleName,
                    'value' => round(($row->total / $totalVehicles) * 100, 1)
                ];
            });

        // Booking status counts
        $statusCancelled = DB::table('booking')->where('bookingStatus','cancelled')->count();
        $statusBooked    = DB::table('booking')->where('bookingStatus','booked')->count();
        $statusPending   = DB::table('booking')->where('bookingStatus','pending')->count();

        // ✅ Pastikan semua variable dihantar ke view
        return view('dashboard.admin_it', [
            'totalUsers'      => $totalUsers,
            'totalStaff'      => $totalStaff,
            'totalVehicles'   => $totalVehicles,
            'newBookings'     => $newBookings,
            'rentedCars'      => $rentedCars,
            'availableCars'   => $availableCars,
            'weeklyLabels'    => $weeklyLabels,
            'weeklyData'      => $weeklyData,
            'carTypes'        => $carTypes,
            'statusCancelled' => $statusCancelled,
            'statusBooked'    => $statusBooked,
            'statusPending'   => $statusPending,
        ]);
    }



    // ============================
    // Admin Finance Dashboard
    // ============================
    public function adminFinance()
    {
        // contoh metric untuk finance admin (payment overview)
        $totalPayments   = DB::table('payment')->count();
        $pendingPayments = DB::table('payment')->where('status','pending')->count();
        $completedPayments = DB::table('payment')->where('status','completed')->count();
        $totalRevenue    = DB::table('payment')->where('status','completed')->sum('amount');

        return view('dashboard.admin_finance', compact(
            'totalPayments','pendingPayments','completedPayments','totalRevenue'
        ));
    }

    // ============================
    // Staff Salesperson Dashboard
    // ============================
    public function staffSalesperson()
    {
        $staffID = auth()->user()->staff->staffID ?? null;

        $bookings = DB::table('booking')->get();
        $statusCancelled = DB::table('booking')->where('bookingStatus','cancelled')->count();
        $statusBooked    = DB::table('booking')->where('bookingStatus','booked')->count();
        $statusPending   = DB::table('booking')->where('bookingStatus','pending')->count();

        $weeklyLabels = ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'];
        $weeklyData   = [3,6,5,7,4,2,8]; // contoh statik

        return view('dashboard.staff_salesperson', compact(
            'bookings','statusCancelled','statusBooked','statusPending',
            'weeklyLabels','weeklyData'
        ));
    }

    // ============================
    // Staff Runner Dashboard
    // ============================
    public function staffRunner()
    {
        $staffID = auth()->user()->staff->staffID ?? null;

        $bookings      = DB::table('booking')->where('staffID',$staffID)->get();
        $assignedToday = DB::table('booking')->where('staffID',$staffID)->whereDate('created_at', now())->count();
        $damageCases   = DB::table('damage_case')->where('staffID',$staffID)->count();

        $statusCancelled = DB::table('booking')->where('staffID',$staffID)->where('bookingStatus','cancelled')->count();
        $statusBooked    = DB::table('booking')->where('staffID',$staffID)->where('bookingStatus','booked')->count();
        $statusPending   = DB::table('booking')->where('staffID',$staffID)->where('bookingStatus','pending')->count();

        $weeklyLabels = ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'];
        $weeklyData   = [2,4,3,5,6,1,7]; // contoh statik

        return view('dashboard.staff_runner', compact(
            'bookings','assignedToday','damageCases',
            'statusCancelled','statusBooked','statusPending',
            'weeklyLabels','weeklyData'
        ));
    }

    // ============================
    // Customer Dashboard
    // ============================
    public function customer()
    {
        $userId = auth()->user()->userID; // pastikan field betul ikut DB
        $booking = DB::table('booking')->where('userID',$userId)->get();

        $totalBookings = $booking->count();

        // kira jumlah hari sewa guna Carbon
        $totalDays = $booking->sum(function($b) {
            return Carbon::parse($b->end_date)->diffInDays(Carbon::parse($b->start_date));
        });

        // cari kereta paling banyak disewa
        $mostCar = $booking
            ->groupBy('carModel')
            ->sortByDesc(fn($group) => count($group))
            ->keys()
            ->first();

        return view('dashboard.customer', compact(
            'booking','totalBookings','totalDays','mostCar'
        ));
    }
}
