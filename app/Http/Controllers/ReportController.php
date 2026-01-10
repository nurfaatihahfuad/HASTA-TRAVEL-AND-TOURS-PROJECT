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
                    ->get();

                $summary = [
                    'total'     => $data->count(),
                    'completed' => $data->where('bookingStatus','completed')->count(),
                    'pending'   => $data->where('bookingStatus','pending')->count(),
                    'cancelled' => $data->where('bookingStatus','cancelled')->count(),
                ];

                return view('admin.report.partials.total_booking', compact('data','summary'));

            case 'revenue':
                $data = DB::table('booking')
                    ->join('vehicles', 'booking.vehicleID', '=', 'vehicles.vehicleID')
                    ->join('payment', 'booking.bookingID', '=', 'payment.bookingID')
                    ->where('booking.bookingStatus', 'completed')
                    ->select(
                        'booking.bookingID',
                        'vehicles.vehicleName',
                        DB::raw('TIMESTAMPDIFF(HOUR, booking.pickup_dateTime, booking.return_dateTime) as duration'),
                        'payment.paymentType',
                        'payment.totalAmount'
                    )
                    ->get();

                $summary = [
                    'total_sales'        => $data->sum('totalAmount'),
                    'total_income'       => $data->sum('totalAmount'),
                    'avg_duration'       => $data->avg('duration'),
                    'completed_payments' => $data->count(),
                ];

                $grouped = $data->groupBy('vehicleName');
                $chart = [
                    'labels' => $grouped->keys()->toArray(),
                    'data'   => $grouped->map(fn($group) => $group->sum('totalAmount'))->values()->toArray()
                ];

                return view('admin.report.partials.revenue', compact('data','summary','chart'));

            case 'top_college':
                $data = $this->getTopCollegeData(new Request());
                return view('admin.report.partials.top_college', compact('data'));

            case 'blacklisted':
                $data = User::where('blacklisted', 1)->get();
                return view('admin.report.partials.blacklisted', compact('data'));
        }
    }

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
            ->get();

        $summary = [
            'total'     => $data->count(),
            'completed' => $data->where('bookingStatus','completed')->count(),
            'pending'   => $data->where('bookingStatus','pending')->count(),
            'cancelled' => $data->where('bookingStatus','cancelled')->count(),
        ];

        return response()->json([
            'data' => $data,
            'summary' => $summary
        ]);
    }

    public function exportTopCollegePdf(Request $request)
    {
        $data = $this->getTopCollegeData($request);

        $pdf = Pdf::loadView('admin.report.partials.top_college', [
            'data' => $data,
            'isPdf' => true
        ]);

        return $pdf->download('TopCollegeReport.pdf');
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
                'Pickup'     => $item->pickup_dateTime,
                'Return'     => $item->return_dateTime,
                'Status'     => $item->bookingStatus,
            ];
        }

        $tempPath = storage_path('app/temp_top_college.xlsx');
        SimpleExcelWriter::create($tempPath)->addRows($rows);

        return Response::download($tempPath)->deleteFileAfterSend(true);
    }

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

        $data = $query->get();

        $summary = [
            'total'     => $data->count(),
            'completed' => $data->where('bookingStatus','completed')->count(),
            'pending'   => $data->where('bookingStatus','pending')->count(),
            'cancelled' => $data->where('bookingStatus','cancelled')->count(),
        ];

        $pdf = Pdf::loadView('admin.report.partials.total_booking', [
            'data' => $data,
            'summary' => $summary,
            'isPdf' => true
        ]);

        return $pdf->download('TotalBookingReport.pdf');
    }

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

        $data = $query->get();

        $rows = [];
        foreach ($data as $item) {
            $rows[] = [
                'Booking ID' => $item->bookingID,
                'User ID'    => $item->userID,
                'Name'       => $item->name,
                'Vehicle'    => $item->vehicleName,
                'Pickup'     => $item->pickup_dateTime,
                'Return'     => $item->return_dateTime,
                'Status'     => $item->bookingStatus,
            ];
        }

        $tempPath = storage_path('app/temp_total_booking.xlsx');
        SimpleExcelWriter::create($tempPath)->addRows($rows);

        return Response::download($tempPath)->deleteFileAfterSend(true);
    }

    // === Export Revenue Report ===
    public function exportRevenuePdf(Request $request)
    {
        $data = DB::table('booking')
            ->join('vehicles', 'booking.vehicleID', '=', 'vehicles.vehicleID')
            ->join('payment', 'booking.bookingID', '=', 'payment.bookingID')
            ->where('booking.bookingStatus', 'successful') // <-- guna status sebenar 
            ->where('payment.paymentStatus', 'pending') // atau 'approved' bila dah verify
            ->select(
                'booking.bookingID',
                'vehicles.vehicleName',
                DB::raw('TIMESTAMPDIFF(HOUR, booking.pickup_dateTime, booking.return_dateTime) as duration'),
                'payment.paymentType',
                'payment.totalAmount'
            )
            ->get();


        $summary = [
            'total_sales'        => $data->sum('totalAmount'),
            'total_income'       => $data->sum('totalAmount'),
            'avg_duration'       => $data->avg('duration'),
            'completed_payments' => $data->count(),
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

        return $pdf->download('RevenueReport.pdf');
    }

    public function exportRevenueExcel(Request $request)
    {
        $data = DB::table('booking')
            ->join('vehicles', 'booking.vehicleID', '=', 'vehicles.vehicleID')
            ->join('payment', 'booking.bookingID', '=', 'payment.bookingID')
            ->where('booking.bookingStatus', 'completed')
            ->select(
                'booking.bookingID',
                'vehicles.vehicleName',
                DB::raw('TIMESTAMPDIFF(HOUR, booking.pickup_dateTime, booking.return_dateTime) as duration'),
                'payment.paymentType',
                'payment.totalAmount'
            )
            ->get();

        $rows = [];
        foreach ($data as $item) {
            $rows[] = [
                'Booking ID'   => $item->bookingID,
                'Vehicle'      => $item->vehicleName,
                'Duration (h)' => $item->duration,
                'Payment Type' => $item->paymentType,
                'Total Amount' => $item->totalAmount,
            ];
        }

        $tempPath = storage_path('app/temp_revenue.xlsx');
        SimpleExcelWriter::create($tempPath)->addRows($rows);

        return Response::download($tempPath)->deleteFileAfterSend(true);
    }
}

    