<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;       // DomPDF facade
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
                $data = Booking::where('bookingStatus','completed')->sum('totalPrice');
                return view('admin.report.partials.revenue', compact('data'));

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
        $data = $this->getTopCollegeData($request);
        return view('admin.report.partials.top_college', compact('data'));
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

        return view('admin.report.partials.total_booking', compact('data','summary'));
    }

    public function exportTopCollegePdf(Request $request)
    {
        $data = $this->getTopCollegeData($request);

        // Pass flag isPdf supaya Blade boleh hide UI elemen
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


    // Helper to reuse query
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
