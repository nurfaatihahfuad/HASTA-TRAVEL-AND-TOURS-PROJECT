<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Response;
use Spatie\SimpleExcel\SimpleExcelWriter;

class ReportController extends Controller
{
    public function index()
    {
        return view('admin.report.index');
    }

    public function show($category, Request $request)
    {
        switch ($category) {
            case 'total_booking':
                $data = DB::table('booking')
                    ->join('user', 'booking.userID', '=', 'user.userID')
                    ->join('vehicles', 'booking.vehicleID', '=', 'vehicles.vehicleID')
                    ->select(
                        'booking.bookingID',
                        'booking.userID',
                        'user.name',
                        'vehicles.vehicleName',
                        'booking.pickup_dateTime',
                        'booking.return_dateTime',
                        'booking.bookingStatus'
                    )
                    ->orderBy('booking.created_at', 'desc')
                    ->get();

                $summary = [
                    'total'     => $data->count(),
                    'successful' => $data->where('bookingStatus', 'successful')->count(),
                    'pending'   => $data->where('bookingStatus', 'pending')->count(),
                    'rejected'  => $data->where('bookingStatus', 'rejected')->count(),
                ];

                if (request()->ajax() || str_contains(request()->url(), '/ajax')) {
                    return response()->json([
                        'data' => $data,
                        'summary' => $summary
                    ]);
                }

                return view('admin.report.partials.total_booking', compact('data','summary'));

            case 'revenue':
                try {
                    $data = DB::table('booking')
                        ->join('vehicles', 'booking.vehicleID', '=', 'vehicles.vehicleID')
                        ->join('payment', 'booking.bookingID', '=', 'payment.bookingID')
                        ->where('booking.bookingStatus', 'successful')
                        ->select(
                            'payment.paymentID',
                            'booking.bookingID',
                            'vehicles.vehicleName',
                            DB::raw('TIMESTAMPDIFF(HOUR, booking.pickup_dateTime, booking.return_dateTime) as duration'),
                            'payment.paymentType',
                            DB::raw('CAST(payment.totalAmount AS DECIMAL(10,2)) as totalAmount'),
                            'payment.paymentStatus'
                        )
                        ->orderBy('booking.bookingID', 'desc')
                        ->get();
                    
                    $summary = [
                        'total_sales'        => $data->sum('totalAmount') ?? 0,
                        'total_income'       => $data->where('paymentStatus', 'approved')->sum('totalAmount') ?? 0,
                        'avg_duration'       => $data->avg('duration') ?? 0,
                        'completed_payments' => $data->where('paymentStatus', 'approved')->count(),
                    ];
                    
                    $chart = ['labels' => [], 'data' => []];
                    
                    if ($data->count() > 0) {
                        $grouped = $data->groupBy('vehicleName');
                        $chart = [
                            'labels' => $grouped->keys()->toArray(),
                            'data'   => $grouped->map(fn($group) => $group->sum('totalAmount'))->values()->toArray()
                        ];
                    }
                    
                    if (request()->ajax() || str_contains(request()->url(), '/ajax')) {
                        return response()->json([
                            'data' => $data,
                            'summary' => $summary,
                            'chart' => $chart
                        ]);
                    }
                    
                    return view('admin.report.partials.revenue', compact('data','summary','chart'));
                    
                } catch (\Exception $e) {
                    \Log::error('Revenue report error: ' . $e->getMessage());
                    
                    $errorData = [
                        'data' => collect([]),
                        'summary' => [
                            'total_sales' => 0,
                            'total_income' => 0,
                            'avg_duration' => 0,
                            'completed_payments' => 0,
                        ],
                        'chart' => ['labels' => [], 'data' => []],
                        'error' => 'Error loading report: ' . $e->getMessage()
                    ];
                    
                    if (request()->ajax() || str_contains(request()->url(), '/ajax')) {
                        return response()->json($errorData, 500);
                    }
                    
                    return view('admin.report.partials.revenue', $errorData);
                }

            case 'top_college':
                try {
                    // DEBUG: Log start
                    \Log::info('=== TOP COLLEGE REPORT START ===');
                    
                    $data = $this->getTopCollegeData(new Request());
                    
                    // DEBUG: Log data count
                    \Log::info('Top College Data Count: ' . $data->count());
                    if ($data->count() > 0) {
                        \Log::info('Sample data: ' . json_encode($data->first()));
                    }
                    
                    if (request()->ajax() || str_contains(request()->url(), '/ajax')) {
                        return response()->json(['data' => $data]);
                    }
                    
                    \Log::info('=== TOP COLLEGE REPORT END ===');
                    return view('admin.report.partials.top_college', compact('data'));
                    
                } catch (\Exception $e) {
                    \Log::error('Top college report error: ' . $e->getMessage());
                    
                    $errorData = [
                        'data' => collect([]),
                        'error' => 'Error loading top college report: ' . $e->getMessage()
                    ];
                    
                    if (request()->ajax() || str_contains(request()->url(), '/ajax')) {
                        return response()->json($errorData, 500);
                    }
                    
                    return view('admin.report.partials.top_college', $errorData);
                }
                
                case 'blacklisted':
                    $search = $request->get('search', '');
                    $reasonFilter = $request->get('reason', '');
                    
                    \Log::info('=== BLACKLIST REPORT QUERY DEBUG ===');
                    
                    // QUERY YANG BENAR:
                    $query = DB::table('blacklistedcust')
                        ->join('user', 'blacklistedcust.customerID', '=', 'user.userID') // Join ke user untuk data pribadi
                        ->leftJoin('customer', 'blacklistedcust.customerID', '=', 'customer.userID') // Join ke customer untuk customerType
                        ->leftJoin('user as admin', 'blacklistedcust.adminID', '=', 'admin.userID') // Join ke admin
                        ->select(
                            'blacklistedcust.blacklistID',
                            'blacklistedcust.reason',
                            'blacklistedcust.adminID',
                            'user.userID',
                            'user.name',
                            'user.email',
                            'user.noHP',     // Phone number dari tabel user
                            'user.noIC',     // IC number dari tabel user
                            'customer.customerType', // customerType dari tabel customer
                            'admin.name as admin_name',
                            DB::raw('CURRENT_DATE() as blacklisted_since')
                        );
                    
                    // DEBUG LOG
                    $debugQuery = clone $query;
                    $debugData = $debugQuery->limit(1)->get();
                    \Log::info('Query result sample:', $debugData->toArray());
                    
                    // Apply search filters
                    if ($search) {
                        $query->where(function($q) use ($search) {
                            $q->where('user.name', 'like', "%{$search}%")
                              ->orWhere('user.email', 'like', "%{$search}%")
                              ->orWhere('user.userID', 'like', "%{$search}%")
                              ->orWhere('user.noIC', 'like', "%{$search}%")
                              ->orWhere('user.noHP', 'like', "%{$search}%");
                        });
                    }
                    
                    if ($reasonFilter) {
                        $query->where('blacklistedcust.reason', 'like', "%{$reasonFilter}%");
                    }
                    
                    $data = $query->orderBy('blacklistedcust.blacklistID', 'desc')->get();
                    
                    // DEBUG: Check jika data ada
                    \Log::info('Total records: ' . $data->count());
                    if ($data->count() > 0) {
                        \Log::info('First record details:', (array) $data->first());
                        \Log::info('Phone from first record: ' . ($data->first()->noHP ?? 'MISSING'));
                        \Log::info('IC from first record: ' . ($data->first()->noIC ?? 'MISSING'));
                    }
                    
                    $summary = [
                        'total' => $data->count(),
                        'students' => $data->where('customerType', 'student')->count(),
                        'staff' => $data->where('customerType', 'staff')->count(),
                    ];
                    
                    if (request()->ajax() || str_contains(request()->url(), '/ajax')) {
                        return response()->json([
                            'data' => $data,
                            'summary' => $summary
                        ]);
                    }
                    
                    return view('admin.report.partials.blacklisted', compact(
                        'data', 'summary', 'search', 'reasonFilter'
                    ));
                    
                default:
                    return redirect()->route('reports.index')->with('error', 'Invalid report category');
            }
        }
        
    // ========== FILTER METHODS ==========
    
    public function filterTopCollege(Request $request)
    {
        try {
            // 1. Cari top college berdasarkan filter
            $topCollegeQuery = DB::table('booking')
                ->join('user', 'booking.userID', '=', 'user.userID')
                ->join('customer', 'user.userID', '=', 'customer.userID')
                ->join('studentCustomer', 'customer.userID', '=', 'studentCustomer.userID')
                ->join('college', 'studentCustomer.collegeID', '=', 'college.collegeID');

            if ($request->month) {
                $topCollegeQuery->whereMonth('booking.pickup_dateTime', $request->month);
            }
            if ($request->year) {
                $topCollegeQuery->whereYear('booking.pickup_dateTime', $request->year);
            }

            $topCollege = $topCollegeQuery
                ->select(
                    'college.collegeName',
                    DB::raw('COUNT(*) as total_bookings')
                )
                ->groupBy('college.collegeName')
                ->orderByDesc('total_bookings')
                ->first();

            if (!$topCollege) {
                return response()->json([]);
            }

            // 2. Ambil data booking untuk top college tersebut
            $query = DB::table('booking')
                ->join('user', 'booking.userID', '=', 'user.userID')
                ->join('vehicles', 'booking.vehicleID', '=', 'vehicles.vehicleID')
                ->join('customer', 'user.userID', '=', 'customer.userID')
                ->join('studentCustomer', 'customer.userID', '=', 'studentCustomer.userID')
                ->join('college', 'studentCustomer.collegeID', '=', 'college.collegeID')
                ->where('college.collegeName', $topCollege->collegeName)
                ->select(
                    'booking.bookingID',
                    'booking.userID',
                    'user.name',
                    'college.collegeName',
                    'vehicles.vehicleName',
                    'booking.pickup_dateTime',
                    'booking.return_dateTime',
                    'booking.bookingStatus'
                );

            if ($request->month) {
                $query->whereMonth('booking.pickup_dateTime', $request->month);
            }
            if ($request->year) {
                $query->whereYear('booking.pickup_dateTime', $request->year);
            }

            $data = $query->orderBy('booking.pickup_dateTime', 'desc')->get();

            return response()->json($data);

        } catch (\Exception $e) {
            \Log::error('Filter top college error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function filterTotalBooking(Request $request)
    {
        $query = DB::table('booking')
            ->join('user', 'booking.userID', '=', 'user.userID')
            ->join('vehicles', 'booking.vehicleID', '=', 'vehicles.vehicleID');

        if ($request->month) {
            $query->whereMonth('booking.pickup_dateTime', $request->month);
        }
        if ($request->year) {
            $query->whereYear('booking.pickup_dateTime', $request->year);
        }

        $data = $query->select(
                'booking.bookingID',
                'booking.userID',
                'user.name',
                'vehicles.vehicleName',
                'booking.pickup_dateTime',
                'booking.return_dateTime',
                'booking.bookingStatus'
            )
            ->orderBy('booking.created_at', 'desc')
            ->get();

        $summary = [
            'total'     => $data->count(),
            'successful' => $data->where('bookingStatus', 'successful')->count(),
            'pending'   => $data->where('bookingStatus', 'pending')->count(),
            'rejected'  => $data->where('bookingStatus', 'rejected')->count(),
        ];

        return response()->json([
            'data' => $data,
            'summary' => $summary
        ]);
    }

    public function filterRevenue(Request $request)
    {
        $query = DB::table('booking')
            ->join('vehicles', 'booking.vehicleID', '=', 'vehicles.vehicleID')
            ->join('payment', 'booking.bookingID', '=', 'payment.bookingID')
            ->where('booking.bookingStatus', 'successful');

        if ($request->month) {
            $query->whereMonth('booking.pickup_dateTime', $request->month);
        }
        if ($request->year) {
            $query->whereYear('booking.pickup_dateTime', $request->year);
        }

        $data = $query->select(
                'payment.paymentID',
                'booking.bookingID',
                'vehicles.vehicleName',
                DB::raw('TIMESTAMPDIFF(HOUR, booking.pickup_dateTime, booking.return_dateTime) as duration'),
                'payment.paymentType',
                DB::raw('CAST(payment.totalAmount AS DECIMAL(10,2)) as totalAmount'),
                'payment.paymentStatus'
            )
            ->orderBy('booking.created_at', 'desc')
            ->get();

        $summary = [
            'total_sales'        => $data->sum('totalAmount') ?? 0,
            'total_income'       => $data->where('paymentStatus', 'approved')->sum('totalAmount') ?? 0,
            'avg_duration'       => $data->avg('duration') ?? 0,
            'completed_payments' => $data->where('paymentStatus', 'approved')->count(),
        ];

        $grouped = $data->groupBy('vehicleName');
        $chart = [
            'labels' => $grouped->keys()->toArray(),
            'data'   => $grouped->map(fn($group) => $group->sum('totalAmount'))->values()->toArray()
        ];

        return response()->json([
            'data' => $data,
            'summary' => $summary,
            'chart' => $chart
        ]);
    }

    public function filterBlacklist(Request $request)
    {
        $search = $request->get('search', '');
        $reasonFilter = $request->get('reason', '');
        
        $query = DB::table('blacklistedcust')
            ->join('user', 'blacklistedcust.customerID', '=', 'user.userID')
            ->leftJoin('customer', 'blacklistedcust.customerID', '=', 'customer.userID')
            ->leftJoin('user as admin', 'blacklistedcust.adminID', '=', 'admin.userID')
            ->select(
                'blacklistedcust.blacklistID',
                'blacklistedcust.reason',
                'blacklistedcust.adminID',
                'user.userID',
                'user.name',
                'user.email',
                'user.noHP',  // Phone
                'user.noIC',  // IC
                'customer.customerType',
                'admin.name as admin_name',
                DB::raw('CURRENT_DATE() as blacklisted_since')
            );
        
            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('user.name', 'like', "%{$search}%")
                      ->orWhere('user.email', 'like', "%{$search}%")
                      ->orWhere('user.userID', 'like', "%{$search}%")
                      ->orWhere('user.noIC', 'like', "%{$search}%")
                      ->orWhere('user.noHP', 'like', "%{$search}%");
                });
            }
            
            if ($reasonFilter) {
                $query->where('blacklistedcust.reason', 'like', "%{$reasonFilter}%");
            }
        
        $data = $query->orderBy('blacklistedcust.blacklistID', 'desc')->get();
        
        $summary = [
            'total' => $data->count(),
            'students' => $data->where('customerType', 'student')->count(),
            'staff' => $data->where('customerType', 'staff')->count(),
        ];
        
        return response()->json([
            'data' => $data,
            'summary' => $summary,
            'search' => $search,
            'reason' => $reasonFilter
        ]);
    }

    

    // ========== EXPORT PDF METHODS ==========

    public function exportTotalBookingPdf(Request $request)
    {
        $query = DB::table('booking')
            ->join('user', 'booking.userID', '=', 'user.userID')
            ->join('vehicles', 'booking.vehicleID', '=', 'vehicles.vehicleID')
            ->select(
                'booking.bookingID',
                'booking.userID',
                'user.name',
                'vehicles.vehicleName',
                'booking.pickup_dateTime',
                'booking.return_dateTime',
                'booking.bookingStatus'
            );

        if ($request->month) {
            $query->whereMonth('booking.pickup_dateTime', $request->month);
        }
        if ($request->year) {
            $query->whereYear('booking.pickup_dateTime', $request->year);
        }

        $data = $query->orderBy('booking.created_at', 'desc')->get();

        $summary = [
            'total'     => $data->count(),
            'successful' => $data->where('bookingStatus', 'successful')->count(),
            'pending'   => $data->where('bookingStatus', 'pending')->count(),
            'rejected'  => $data->where('bookingStatus', 'rejected')->count(),
        ];

        $pdf = Pdf::loadView('admin.report.partials.total_booking', [
            'data' => $data,
            'summary' => $summary,
            'isPdf' => true
        ]);

        return $pdf->download('TotalBookingReport_' . date('Ymd_His') . '.pdf');
    }

    public function exportRevenuePdf(Request $request)
    {
        $query = DB::table('booking')
            ->join('vehicles', 'booking.vehicleID', '=', 'vehicles.vehicleID')
            ->join('payment', 'booking.bookingID', '=', 'payment.bookingID')
            ->where('booking.bookingStatus', 'successful');

        if ($request->month) {
            $query->whereMonth('booking.pickup_dateTime', $request->month);
        }
        if ($request->year) {
            $query->whereYear('booking.pickup_dateTime', $request->year);
        }

        $data = $query->select(
                'payment.paymentID',
                'booking.bookingID',
                'vehicles.vehicleName',
                DB::raw('TIMESTAMPDIFF(HOUR, booking.pickup_dateTime, booking.return_dateTime) as duration'),
                'payment.paymentType',
                DB::raw('CAST(payment.totalAmount AS DECIMAL(10,2)) as totalAmount'),
                'payment.paymentStatus'
            )
            ->orderBy('booking.created_at', 'desc')
            ->get();

        $summary = [
            'total_sales'        => $data->sum('totalAmount') ?? 0,
            'total_income'       => $data->where('paymentStatus', 'approved')->sum('totalAmount') ?? 0,
            'avg_duration'       => $data->avg('duration') ?? 0,
            'completed_payments' => $data->where('paymentStatus', 'approved')->count(),
        ];

        $grouped = $data->groupBy('vehicleName');
        $chart = [
            'labels' => $grouped->keys()->toArray(),
            'data'   => $grouped->map(fn($group) => $group->sum('totalAmount'))->values()->toArray()
        ];

        $pdf = Pdf::loadView('admin.report.partials.revenue', [
            'data' => $data,
            'summary' => $summary,
            'chart' => $chart,
            'isPdf' => true
        ]);

        return $pdf->download('RevenueReport_' . date('Ymd_His') . '.pdf');
    }

    public function exportTopCollegePdf(Request $request)
    {
        $data = $this->getTopCollegeData($request);

        $pdf = Pdf::loadView('admin.report.partials.top_college', [
            'data' => $data,
            'isPdf' => true
        ]);

        return $pdf->download('TopCollegeReport_' . date('Ymd_His') . '.pdf');
    }

    public function exportBlacklistPdf(Request $request)
    {
        $search = $request->get('search', '');
        $reasonFilter = $request->get('reason', '');
        
        // PERBAIKI QUERY: Gunakan yang sama dengan method show()
        $query = DB::table('blacklistedcust')
            ->join('user', 'blacklistedcust.customerID', '=', 'user.userID')
            ->leftJoin('customer', 'blacklistedcust.customerID', '=', 'customer.userID')
            ->leftJoin('user as admin', 'blacklistedcust.adminID', '=', 'admin.userID')
            ->select(
                'blacklistedcust.blacklistID',
                'blacklistedcust.reason',
                'blacklistedcust.adminID',
                'user.userID',
                'user.name',
                'user.email',
                'user.noHP',
                'user.noIC',
                'customer.customerType',
                'admin.name as admin_name',
                DB::raw('CURRENT_DATE() as blacklisted_since')
            );
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('user.name', 'like', "%{$search}%")
                ->orWhere('user.email', 'like', "%{$search}%")
                ->orWhere('user.userID', 'like', "%{$search}%")
                ->orWhere('user.noIC', 'like', "%{$search}%")
                ->orWhere('user.noHP', 'like', "%{$search}%");
            });
        }
        
        if ($reasonFilter) {
            $query->where('blacklistedcust.reason', 'like', "%{$reasonFilter}%");
        }
        
        $data = $query->orderBy('blacklistedcust.blacklistID', 'desc')->get(); 
        
        $summary = [
            'total' => $data->count(),
            'students' => $data->where('customerType', 'student')->count(),
            'staff' => $data->where('customerType', 'staff')->count(),
        ];
        
        $pdf = Pdf::loadView('admin.report.partials.blacklisted', [
            'data' => $data,
            'summary' => $summary,
            'isPdf' => true,
            'search' => $search,
            'reasonFilter' => $reasonFilter
        ]);
        
        return $pdf->download('BlacklistReport_' . date('Ymd_His') . '.pdf');
    }

    // ========== EXPORT EXCEL METHODS ==========

    public function exportTotalBookingExcel(Request $request)
    {
        $query = DB::table('booking')
            ->join('user', 'booking.userID', '=', 'user.userID')
            ->join('vehicles', 'booking.vehicleID', '=', 'vehicles.vehicleID')
            ->select(
                'booking.bookingID',
                'booking.userID',
                'user.name',
                'vehicles.vehicleName',
                'booking.pickup_dateTime',
                'booking.return_dateTime',
                'booking.bookingStatus'
            );

        if ($request->month) {
            $query->whereMonth('booking.pickup_dateTime', $request->month);
        }
        if ($request->year) {
            $query->whereYear('booking.pickup_dateTime', $request->year);
        }

        $data = $query->orderBy('booking.created_at', 'desc')->get();

        $rows = [];
        foreach ($data as $item) {
            $rows[] = [
                'Booking ID' => $item->bookingID,
                'User ID'    => $item->userID,
                'Name'       => $item->name,
                'Vehicle'    => $item->vehicleName,
                'Pickup DateTime'     => $item->pickup_dateTime,
                'Return DateTime'     => $item->return_dateTime,
                'Status'     => ucfirst($item->bookingStatus),
            ];
        }

        $tempPath = storage_path('app/temp_total_booking_' . time() . '.xlsx');
        SimpleExcelWriter::create($tempPath)->addRows($rows);

        return Response::download($tempPath, 'TotalBookingReport_' . date('Ymd_His') . '.xlsx')
            ->deleteFileAfterSend(true);
    }

    public function exportRevenueExcel(Request $request)
    {
        $query = DB::table('booking')
            ->join('vehicles', 'booking.vehicleID', '=', 'vehicles.vehicleID')
            ->join('payment', 'booking.bookingID', '=', 'payment.bookingID')
            ->where('booking.bookingStatus', 'successful');

        if ($request->month) {
            $query->whereMonth('booking.pickup_dateTime', $request->month);
        }
        if ($request->year) {
            $query->whereYear('booking.pickup_dateTime', $request->year);
        }

        $data = $query->select(
                'payment.paymentID',
                'booking.bookingID',
                'vehicles.vehicleName',
                DB::raw('TIMESTAMPDIFF(HOUR, booking.pickup_dateTime, booking.return_dateTime) as duration'),
                'payment.paymentType',
                DB::raw('CAST(payment.totalAmount AS DECIMAL(10,2)) as totalAmount'),
                'payment.paymentStatus'
            )
            ->orderBy('booking.created_at', 'desc')
            ->get();

        $rows = [];
        foreach ($data as $item) {
            $rows[] = [
                'Payment ID'   => $item->paymentID,
                'Booking ID'   => $item->bookingID,
                'Vehicle'      => $item->vehicleName,
                'Duration (hours)' => $item->duration,
                'Payment Type' => $item->paymentType,
                'Total Amount (RM)' => number_format($item->totalAmount, 2),
                'Payment Status' => ucfirst($item->paymentStatus),
            ];
        }

        $tempPath = storage_path('app/temp_revenue_' . time() . '.xlsx');
        SimpleExcelWriter::create($tempPath)->addRows($rows);

        return Response::download($tempPath, 'RevenueReport_' . date('Ymd_His') . '.xlsx')
            ->deleteFileAfterSend(true);
    }

    public function exportTopCollegeExcel(Request $request)
    {
        $data = $this->getTopCollegeData($request);

        $rows = [];
        foreach ($data as $item) {
            $rows[] = [
                'Booking ID' => $item->bookingID,
                'User ID'    => $item->userID,
                'Name'       => $item->name,
                'College'    => $item->collegeName,
                'Vehicle'    => $item->vehicleName,
                'Pickup DateTime' => $item->pickup_dateTime,
                'Return DateTime' => $item->return_dateTime,
                'Status'     => ucfirst($item->bookingStatus),
            ];
        }

        $tempPath = storage_path('app/temp_top_college_' . time() . '.xlsx');
        SimpleExcelWriter::create($tempPath)->addRows($rows);

        return Response::download($tempPath, 'TopCollegeReport_' . date('Ymd_His') . '.xlsx')
            ->deleteFileAfterSend(true);
    }

    public function exportBlacklistExcel(Request $request)
    {
        $search = $request->get('search', '');
        $reasonFilter = $request->get('reason', '');
        
        // PERBAIKI QUERY: Gunakan yang sama dengan method show()
        $query = DB::table('blacklistedcust')
            ->join('user', 'blacklistedcust.customerID', '=', 'user.userID')
            ->leftJoin('customer', 'blacklistedcust.customerID', '=', 'customer.userID')
            ->leftJoin('user as admin', 'blacklistedcust.adminID', '=', 'admin.userID')
            ->select(
                'blacklistedcust.blacklistID',
                'blacklistedcust.reason',
                'blacklistedcust.adminID',
                'user.userID',
                'user.name',
                'user.email',
                'user.noHP',
                'user.noIC',
                'customer.customerType',
                'admin.name as admin_name',
                DB::raw('CURRENT_DATE() as blacklisted_since')
            );
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('user.name', 'like', "%{$search}%")
                ->orWhere('user.email', 'like', "%{$search}%")
                ->orWhere('user.userID', 'like', "%{$search}%")
                ->orWhere('user.noIC', 'like', "%{$search}%")
                ->orWhere('user.noHP', 'like', "%{$search}%");
            });
        }
        
        if ($reasonFilter) {
            $query->where('blacklistedcust.reason', 'like', "%{$reasonFilter}%");
        }
        
        $data = $query->orderBy('blacklistedcust.blacklistID', 'desc')->get();
        
        $rows = [];
        foreach ($data as $item) {
            $rows[] = [
                'Blacklist ID' => $item->blacklistID,
                'Customer ID'  => $item->userID,
                'Name'         => $item->name,
                'Email'        => $item->email,
                'Phone'        => $item->noHP ? $item->noHP : 'N/A',
                'IC Number'    => $item->noIC ? $item->noIC : 'N/A',
                'Customer Type'=> ucfirst($item->customerType),
                'Reason'       => $item->reason,
                'Admin Name'   => $item->admin_name ?? 'N/A',
                'Admin ID'     => $item->adminID,
                'Blacklisted Since' => $item->blacklisted_since,
            ];
        }
        
        $tempPath = storage_path('app/temp_blacklist_' . time() . '.xlsx');
        SimpleExcelWriter::create($tempPath)->addRows($rows);
        
        return Response::download($tempPath, 'BlacklistReport_' . date('Ymd_His') . '.xlsx')
            ->deleteFileAfterSend(true);
    }


    // ========== PRIVATE HELPER METHODS ==========

    private function getTopCollegeData(Request $request)
    {
        // 1. Cari kolej dengan jumlah booking terbanyak
        $topCollegeQuery = DB::table('booking')
            ->join('user', 'booking.userID', '=', 'user.userID')
            ->join('customer', 'user.userID', '=', 'customer.userID')
            ->join('studentCustomer', 'customer.userID', '=', 'studentCustomer.userID')
            ->join('college', 'studentCustomer.collegeID', '=', 'college.collegeID');

        // Apply month/year filter jika ada
        if ($request->month) {
            $topCollegeQuery->whereMonth('booking.pickup_dateTime', $request->month);
        }
        if ($request->year) {
            $topCollegeQuery->whereYear('booking.pickup_dateTime', $request->year);
        }

        $topCollege = $topCollegeQuery
            ->select(
                'college.collegeName',
                DB::raw('COUNT(*) as total_bookings')
            )
            ->groupBy('college.collegeName')
            ->orderByDesc('total_bookings')
            ->first(); // Ambil yang pertama (tertinggi)

        // Jika tak ada data, return kosong
        if (!$topCollege) {
            return collect();
        }

        // 2. Ambil SEMUA booking untuk kolej tersebut
        $query = DB::table('booking')
            ->join('user', 'booking.userID', '=', 'user.userID')
            ->join('vehicles', 'booking.vehicleID', '=', 'vehicles.vehicleID')
            ->join('customer', 'user.userID', '=', 'customer.userID')
            ->join('studentCustomer', 'customer.userID', '=', 'studentCustomer.userID')
            ->join('college', 'studentCustomer.collegeID', '=', 'college.collegeID')
            ->where('college.collegeName', $topCollege->collegeName) // Filter hanya untuk top college
            ->select(
                'booking.bookingID',
                'booking.userID',
                'user.name',
                'college.collegeName',
                'vehicles.vehicleName',
                'booking.pickup_dateTime',
                'booking.return_dateTime',
                'booking.bookingStatus'
            );

        // Apply month/year filter lagi untuk data spesifik
        if ($request->month) {
            $query->whereMonth('booking.pickup_dateTime', $request->month);
        }
        if ($request->year) {
            $query->whereYear('booking.pickup_dateTime', $request->year);
        }

        return $query->orderBy('booking.pickup_dateTime', 'desc')->get();
        
    }

}