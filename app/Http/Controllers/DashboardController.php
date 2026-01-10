<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\Booking;
use App\Models\Vehicle;
use App\Models\Customer;
use App\Models\BlacklistedCust;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class DashboardController extends Controller
{

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
    
    //yg ni Auni dh ubah jadi coding asal semula
    public function staffSalesperson()
    {
        $staffID = auth()->user()->staff->staffID ?? null;

        //$bookings = DB::table('booking')->get();
        /*$bookings = Booking::with(['payments', 'vehicle'])
            ->orderBy('created_at', 'desc')
            ->get();*/
        // Try different column names SINI
        $latestBookings = DB::table('booking')
            ->leftJoin('payment', function($join) {
                $join->on('booking.bookingID', '=', 'payment.bookingID');
            })
            ->leftJoin('vehicles', 'booking.vehicleID', '=', 'vehicles.vehicleID')
            ->leftJoin('user', 'booking.userID', '=', 'user.userID')
            ->select(
                'booking.*',
                'user.name',
                'vehicles.vehicleName',
                'vehicles.plateNo',
                'payment.receipt_file_path',
                'payment.paymentStatus',
                'payment.paymentStatus as payment_status', // try both
                'payment.amountPaid'
            )
            ->orderBy('booking.created_at', 'desc')
            ->limit(5) // Only show 5 recent for dashboard
            ->get();

        $statusCancelled = DB::table('booking')->where('bookingStatus','cancelled')->count();
        $statusBooked    = DB::table('booking')->where('bookingStatus','booked')->count();
        $statusPending   = DB::table('booking')->where('bookingStatus','pending')->count();
        $bookingsToday = DB::table('booking')->whereDate('created_at', now())->count();

        $weeklyLabels = ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'];
        $weeklyData   = [3,6,5,7,4,2,8]; // contoh statik

        return view('dashboard.staff_salesperson', compact(
            'latestBookings','bookingsToday','statusCancelled','statusBooked','statusPending',
            'weeklyLabels','weeklyData'
        ));
    }

    // Display bookings for verification (Staff)
    /**
 * Display all bookings for verification (dedicated page)
 */
public function verifyBookings()
{
    // Get all bookings with filters
    $bookings = DB::table('booking')
        ->leftJoin('payment', function($join) {
            $join->on('booking.bookingID', '=', 'payment.bookingID');
        })
        ->leftJoin('vehicles', 'booking.vehicleID', '=', 'vehicles.vehicleID')
        ->leftJoin('user', 'booking.userID', '=', 'user.userID')
        ->select(
            'booking.*',
            'user.name',
            'vehicles.vehicleName',
            'vehicles.plateNo',
            'payment.receipt_file_path',
            'payment.paymentStatus',
            'payment.amountPaid',
            'booking.created_at as booking_date'
        )
        ->orderBy('booking.created_at', 'desc')
        ->paginate(20); // Use pagination for better performance

    // Statistics for the verification page
    $totalBookings = DB::table('booking')->count();
    $pendingVerification = DB::table('booking')
        ->leftJoin('payment', 'booking.bookingID', '=', 'payment.bookingID')
        ->where('payment.paymentStatus', 'pending')
        ->orWhereNull('payment.paymentStatus')
        ->count();
    

    return view('staff.payment.record', compact(
        'bookings',
        'totalBookings',
        'pendingVerification'
    ));
}

    // ============================
    // Staff Runner Dashboard
    // ============================
    /*public function staffRunner()
    {
        $staffID = auth()->user()->staff->staffID ?? null;

        // attributes tak sama dengan table
        //$bookings      = DB::table('booking')->where('staffID',$staffID)->get();
        //$assignedToday = DB::table('booking')->where('staffID',$staffID)->whereDate('created_at', now())->count();
        //$damageCases   = DB::table('damage_case')->where('staffID',$staffID)->count();

        $statusCancelled = DB::table('booking')->where('bookingStatus','cancelled')->count();
        $statusBooked    = DB::table('booking')->where('bookingStatus','booked')->count();
        $statusPending   = DB::table('booking')->where('bookingStatus','pending')->count();

        $weeklyLabels = ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'];
        $weeklyData   = [2,4,3,5,6,1,7]; // contoh statik

        return view('dashboard.staff_runner', compact(
            'statusCancelled','statusBooked','statusPending',
            'weeklyLabels','weeklyData'
        ));
    }*/
    public function staffRunner()
    {
        // ============================
        // 1. KPI INSPECTION
        // ============================
        $totalInspections = Inspection::count();

        $inspectionToday = Inspection::whereDate('created_at', now())->count();

        $damagedCount = Inspection::where('damageDetected', 1)->count();
        $okCount      = Inspection::where('damageDetected', 0)->count();

        // ============================
        // 2. WEEKLY INSPECTION (BAR)
        // ============================
        $weeklyLabels = ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'];
        
        $weeklyData = [];
        foreach ($weeklyLabels as $day) {
            $weeklyData[] = Inspection::whereRaw('DAYNAME(created_at) = ?', [$day])->count();
        }

        // ============================
        // 3. ALL INSPECTIONS (TABLE)
        // ============================
        $inspections = Inspection::with('vehicle')->orderBy('created_at', 'desc')->get();

        return view('dashboard.staff_runner', compact(
            'totalInspections',
            'inspectionToday',
            'damagedCount',
            'okCount',
            'weeklyLabels',
            'weeklyData',
            'inspections'
        ));
    }

    

    // ============================
    // Customer Dashboard
    // ============================
    public function customer()
    {
        /*$userId = auth()->user()->userID; // pastikan field betul ikut DB
        $booking = DB::table('booking')->where('userID',$userId)->get();

        $totalBookings = $booking->count();

        // kira jumlah hari sewa guna Carbon
        $totalDays = $booking->sum(function($b) {
            return Carbon::parse($b->pickup_dateTime)
                ->diffInDays(Carbon::parse($b->return_dateTime));
        });        

        // cari kereta paling banyak disewa
        $mostCar = $booking
            ->groupBy('carModel')
            ->sortByDesc(fn($group) => count($group))
            ->keys()
            ->first();
        $booking = DB::table('booking')
            ->join('vehicles', 'booking.vehicleID', '=', 'vehicles.vehicleID')
            ->where('userID', $userId)
            ->select('booking.*', 'vehicles.vehicleName as carModel')
            ->get();    

        return view('dashboard.customer', compact(
            'booking','totalBookings','totalDays','mostCar'
        ));*/
        $user = Auth::user();
        $customer = $user->customer;
        
        // Get bookings (example - adjust based on your actual Booking model)
        $bookings = $user->bookings()->latest()->take(5)->get();
        $upcomingBookings = $user->bookings()
            ->where('pickup_dateTime', '>=', now())
            ->orderBy('pickup_dateTime')
            ->take(3)
            ->get();
        
        // Calculate metrics
        $totalBookings = $user->bookings()->count();
        $activeBookings = $user->bookings()->where('bookingStatus', 'successful')->count();
        
        // Calculate total days (example logic)
        $totalDays = $user->bookings()
            ->where('bookingStatus', 'successful')
            ->get()
            ->sum(function($booking) {
                $pickup = \Carbon\Carbon::parse($booking->pickup_dateTime);
                $return = \Carbon\Carbon::parse($booking->return_dateTime);
                return $return->diffInDays($pickup);
            });
        
        // Most rented car
        $mostCar = $user->bookings()
        ->selectRaw('vehicles.vehicleName as carModel, count(*) as count')
        ->join('vehicles', 'booking.vehicleID', '=', 'vehicles.vehicleID')
        ->groupBy('vehicles.vehicleName')
        ->orderByDesc('count')
        ->value('carModel');

        // Most rented car with details
        $mostRentedVehicle = $user->bookings()
            ->selectRaw('vehicles.*, count(*) as rental_count')
            ->join('vehicles', 'booking.vehicleID', '=', 'vehicles.vehicleID')
            ->groupBy('vehicles.vehicleID', 'vehicles.vehicleName', 
                    'vehicles.plateNo', 'vehicles.year','vehicles.price_per_day','price_per_hour',
                    'vehicles.available','vehicles.image_url','vehicles.description',
                    'vehicles.created_at','vehicles.updated_at')
            ->orderByDesc('rental_count')
            ->first();
        
        // Get statistics
        $totalBookings = $user->bookings()->count();
        /*$activeBookings = $user->bookings()
            ->whereIn('status', 'successful')
            ->count();*/
        $completedBookings = $user->bookings()
            ->where('bookingStatus', 'successful')
            ->count();
        
        // Recent bookings
        $recentBookings = $user->bookings()
            ->with('vehicle')
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.customer', [
            'customer' => $customer,
            'bookings' => $bookings,
            'upcomingBookings' => $upcomingBookings,
            'totalBookings' => $totalBookings,
            //'activeBookings' => $activeBookings,
            'completedBookings' => $completedBookings,
            'totalDays' => $totalDays,
            'mostCar' => $mostCar,
        ]);
    }

    public function customerDashboard()
    {
        $userID = auth()->id();

       $bookings = Booking::with('vehicle') 
                    ->where('userID', $userID) 
                    ->orderBy('created_at', 'desc') ->get();
                    
        return view('customer.dashboard', compact('bookings'));
    }

    public function manageCustomers(Request $request)
    {
        // Query for customers with join to customer table
        $query = DB::table('user')
            ->join('customer', 'user.userID', '=', 'customer.userID') // Join with customer table
            ->select(
                'user.userID',
                'user.name',
                'user.email',
                'user.noHP',
                'customer.customerStatus as status', // Get status from customer table
            );

        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('user.name', 'like', "%{$search}%")
                ->orWhere('user.email', 'like', "%{$search}%")
                ->orWhere('user.noHP', 'like', "%{$search}%");
            });
        }

        // Apply status filter (now from customer table)
        if ($request->filled('status')) {
            $query->where('customer.customerStatus', $request->status);
        }

        // Order and paginate
        $customers = $query
            ->paginate(20)
            ->withQueryString();

        // Get booking statistics for each customer
        foreach ($customers as $customer) {
            $bookingStats = DB::table('booking')
                ->select(
                    DB::raw('COUNT(*) as total_bookings'),
                    DB::raw('SUM(CASE WHEN bookingStatus = "successful" THEN 1 ELSE 0 END) as successful_bookings'),
                    DB::raw('MAX(created_at) as last_booking_date')
                )
                ->where('userID', $customer->userID)
                ->first();
            
            $customer->total_bookings = $bookingStats->total_bookings ?? 0;
            $customer->successful_bookings = $bookingStats->successful_bookings ?? 0;
            $customer->last_booking_date = $bookingStats->last_booking_date;
        }

        // Statistics (updated to use customer table)
        $totalCustomers = DB::table('customer')->count();
        $activeCustomers = DB::table('customer')->where('customerStatus', 'active')->count();

        return view('admin.customers.index', compact(
            'customers',
            'totalCustomers',
            'activeCustomers'
        ));
    }

    // View single customer details
    public function viewCustomer($userId)
    {
        $customer = DB::table('user')
            ->join('customer', 'user.userID', '=', 'customer.userID')
            ->select('user.*', 'customer.*', 'customer.customerStatus as status')
            ->where('user.userID', $userId)
            ->first();

        if (!$customer) {
            abort(404, 'Customer not found');
        }

        // Get blacklist data with admin info
        $blacklistData = null;
        if ($customer->status == 'blacklisted') {
            $blacklistData = DB::table('blacklistedcust')
                ->leftJoin('user as admin', 'blacklistedcust.adminID', '=', 'admin.userID')
                ->select('blacklistedcust.*', 'admin.name as admin_name')
                ->where('blacklistedcust.customerID', $userId)
                ->first();
        }
        
        // Add blacklist data to customer object
        $customer->blacklistData = $blacklistData;

        // Get customer bookings
        $bookings = DB::table('booking')
            ->leftJoin('vehicles', 'booking.vehicleID', '=', 'vehicles.vehicleID')
            ->leftJoin('payment', 'booking.bookingID', '=', 'payment.bookingID')
            ->select(
                'booking.*',
                'vehicles.vehicleName',
                'vehicles.plateNo',
                'payment.paymentStatus',
                'payment.amountPaid',
                'payment.receipt_file_path'
            )
            ->where('booking.userID', $userId)
            ->orderBy('booking.created_at', 'desc')
            ->get();

        // Get statistics for this customer
        $totalBookings = $bookings->count();
        $totalSpent = $bookings->sum('amountPaid');
        $favoriteCar = $bookings->groupBy('vehicleName')
            ->sortByDesc(function($group) {
                return $group->count();
            })
            ->keys()
            ->first();

        return view('admin.customers.view', compact(
            'customer',
            'bookings',
            'totalBookings',
            'totalSpent',
            'favoriteCar'
        ));
    }

    // Edit customer
    public function editCustomer($userId)
    {
        $customer = DB::table('user')
            ->join('customer', 'user.userID', '=', 'customer.userID')
            ->select('user.*', 'customer.*', 'customer.customerStatus as status')
            ->where('user.userID', $userId)
            ->first();
        
        if (!$customer) {
            abort(404);
        }

        return view('admin.customers.edit', compact('customer'));
    }

    // Update customer
    public function updateCustomer(Request $request, $userId)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:user,email,' . $userId . ',userID',
            'noHP' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'status' => 'required|in:active,inactive,suspended',
        ]);

        // Start transaction for updating both tables
        DB::beginTransaction();

        try {
            // Update user table
            DB::table('user')
                ->where('userID', $userId)
                ->update([
                    'name' => $request->name,
                    'email' => $request->email,
                    'noHP' => $request->noHP,
                ]);

            // Update customer table
            DB::table('customer')
                ->where('userID', $userId)
                ->update([
                    'customerStatus' => $request->status // Update customerStatus
                ]);

            DB::commit();

            return redirect()->route('admin.customers.view', $userId)
                ->with('success', 'Customer updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to update customer: ' . $e->getMessage());
        }
    }

    // Toggle customer status
    public function toggleCustomerStatus(Request $request, $userId)
    {
        // Find customer using model
        $customer = Customer::where('userID', $userId)->first();

        if (!$customer) {
            abort(404, 'Customer not found');
        }

        // Check if customer is already blacklisted
        if ($customer->customerStatus == 'blacklisted') {
            return back()->with('error', 'This customer is already blacklisted.');
        }

        // Only allow changing from active to blacklisted
        if ($customer->customerStatus == 'active') {
            // Validate the reason
            $validated = $request->validate([
                'reason' => 'required|string|max:100|min:10'
            ]);

            DB::beginTransaction();
            
            try {
                // Update customer status
                $customer->update(['customerStatus' => 'blacklisted']);
                
                // Generate unique blacklist ID
                do {
                    $blacklistID = 'BL' . strtoupper(Str::random(4));
                } while (BlacklistedCust::where('blacklistID', $blacklistID)->exists());
                
                // Check if blacklist record already exists
                $existingBlacklist = BlacklistedCust::where('customerID', $userId)->first();
                
                if ($existingBlacklist) {
                    // Update existing record
                    $existingBlacklist->update([
                        'reason' => $validated['reason'],
                        'adminID' => auth()->id()
                        // No blacklistDate field
                    ]);
                } else {
                    // Create new blacklist record WITHOUT blacklistDate
                    BlacklistedCust::create([
                        'blacklistID' => $blacklistID,
                        'customerID' => $userId,
                        'reason' => $validated['reason'],
                        'adminID' => auth()->id()
                        // No blacklistDate field
                    ]);

                    
                }

                DB::commit();
                
                // Log the action with current timestamp
                \Log::info('Customer blacklisted', [
                    'customerID' => $userId,
                    'blacklistID' => $blacklistID,
                    'adminID' => auth()->id(),
                    'reason' => $validated['reason'],
                    'timestamp' => now()->toDateTimeString()
                ]);
                
                return back()->with('success', 
                    'Customer has been blacklisted successfully. ' .
                    'Blacklist ID: ' . $blacklistID);

            } catch (\Exception $e) {
                DB::rollBack();
                
                \Log::error('Failed to blacklist customer', [
                    'customerID' => $userId,
                    'adminID' => auth()->id(),
                    'error' => $e->getMessage()
                ]);
                
                return back()->with('error', 
                    'Failed to blacklist customer: ' . $e->getMessage());
            }
        }

        return back()->with('error', 
            'Only active customers can be blacklisted. Current status: ' . 
            ucfirst($customer->customerStatus));
    }

    public function blacklistedCustomers(Request $request)
    {
        // Get search and filter parameters
        $search = $request->get('search');
        
        // Base query for blacklisted customers
        $query = DB::table('customer')
            ->join('user', 'customer.userID', '=', 'user.userID')
            ->join('blacklistedcust', 'customer.userID', '=', 'blacklistedcust.customerID')
            ->leftJoin('user as admin', 'blacklistedcust.adminID', '=', 'admin.userID')
            ->select(
                'user.userID',
                'user.name',
                'user.email',
                'user.noHP',
                'user.noIC',
                'customer.customerType',
                'customer.customerStatus',
                'blacklistedcust.blacklistID',
                'blacklistedcust.reason',
                'blacklistedcust.adminID',
                'admin.name as adminName'
            )
            ->where('customer.customerStatus', 'blacklisted');

        // Apply search filter
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('user.name', 'like', "%{$search}%")
                ->orWhere('user.email', 'like', "%{$search}%")
                ->orWhere('user.noHP', 'like', "%{$search}%")
                ->orWhere('user.userID', 'like', "%{$search}%")
                ->orWhere('blacklistedcust.blacklistID', 'like', "%{$search}%");
            });
        }


        // Get paginated results
        $blacklistedCustomers = $query->paginate(20)->withQueryString();

        // Get statistics
        $totalBlacklisted = DB::table('customer')
            ->where('customerStatus', 'blacklisted')
            ->count();
        
        $totalCustomers = DB::table('customer')->count();
        
        $blacklistPercentage = $totalCustomers > 0 
            ? round(($totalBlacklisted / $totalCustomers) * 100, 2) 
            : 0;

        return view('admin.blacklisted.index', compact(
            'blacklistedCustomers',
            'totalBlacklisted',
            'totalCustomers',
            'blacklistPercentage',
            'search',
        ));
    }
}

