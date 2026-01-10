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

    public function show($category)
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

                // PERUBAHAN: Sesuaikan dengan ENUM yang ada
                $summary = [
                    'total'     => $data->count(),
                    'successful' => $data->where('bookingStatus', 'successful')->count(), // changed from 'completed'
                    'pending'   => $data->where('bookingStatus', 'pending')->count(),
                    'rejected'  => $data->where('bookingStatus', 'rejected')->count(), // changed from 'cancelled'
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
                    // PERUBAHAN: Guna 'successful' bukan 'completed'
                    $data = DB::table('booking')
                        ->join('vehicles', 'booking.vehicleID', '=', 'vehicles.vehicleID')
                        ->join('payment', 'booking.bookingID', '=', 'payment.bookingID')
                        ->where('booking.bookingStatus', 'successful') // CHANGED: 'successful' bukan 'completed'
                        // REMOVED: ->whereIn('payment.paymentStatus', ['approved'])
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
                    
                    // Create chart data
                    $chart = ['labels' => [], 'data' => []];
                    
                    if ($data->count() > 0) {
                        $grouped = $data->groupBy('vehicleName');
                        $chart = [
                            'labels' => $grouped->keys()->toArray(),
                            'data'   => $grouped->map(fn($group) => $group->sum('totalAmount'))->values()->toArray()
                        ];
                    }
                    
                    // AJAX response
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
                $data = $this->getTopCollegeData(new Request());
                
                if (request()->ajax() || str_contains(request()->url(), '/ajax')) {
                    return response()->json(['data' => $data]);
                }
                
                return view('admin.report.partials.top_college', compact('data'));

            case 'blacklisted':
                $data = User::where('blacklisted', 1)->get();
                
                if (request()->ajax() || str_contains(request()->url(), '/ajax')) {
                    return response()->json(['data' => $data]);
                }
                
                return view('admin.report.partials.blacklisted', compact('data'));
                
            default:
                return redirect()->route('reports.index')->with('error', 'Invalid report category');
        }
    }

    // ========== FILTER METHODS ==========
    
    public function filterTopCollege(Request $request)
    {
        $month = $request->month;
        $year = $request->year;
    
        $data = Booking::where('collegeName', '!=', null)
            ->when($month, fn($q) => $q->whereMonth('pickup_dateTime', $month))
            ->when($year, fn($q) => $q->whereYear('pickup_dateTime', $year))
            ->get();
    
        return response()->json($data);
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

        // PERUBAHAN: Sesuaikan dengan ENUM
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
        // PERUBAHAN: Guna 'successful' bukan 'completed'
        $query = DB::table('booking')
            ->join('vehicles', 'booking.vehicleID', '=', 'vehicles.vehicleID')
            ->join('payment', 'booking.bookingID', '=', 'payment.bookingID')
            ->where('booking.bookingStatus', 'successful'); // CHANGED

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

        // PERUBAHAN: Sesuaikan dengan ENUM
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
        // PERUBAHAN: Guna 'successful' bukan 'completed'
        $query = DB::table('booking')
            ->join('vehicles', 'booking.vehicleID', '=', 'vehicles.vehicleID')
            ->join('payment', 'booking.bookingID', '=', 'payment.bookingID')
            ->where('booking.bookingStatus', 'successful'); // CHANGED

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
        // PERUBAHAN: Guna 'successful' bukan 'completed'
        $query = DB::table('booking')
            ->join('vehicles', 'booking.vehicleID', '=', 'vehicles.vehicleID')
            ->join('payment', 'booking.bookingID', '=', 'payment.bookingID')
            ->where('booking.bookingStatus', 'successful'); // CHANGED

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

    // ========== PRIVATE HELPER METHODS ==========

    private function getTopCollegeData(Request $request)
    {
        $query = DB::table('booking')
            ->join('user', 'booking.userID', '=', 'user.userID')
            ->join('vehicles', 'booking.vehicleID', '=', 'vehicles.vehicleID')
            ->join('customer', 'user.userID', '=', 'customer.userID')
            ->join('studentCustomer', 'customer.userID', '=', 'studentCustomer.userID')
            ->join('college', 'studentCustomer.collegeID', '=', 'college.collegeID')
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

        return $query->orderBy('college.collegeName')->get();
    }
}