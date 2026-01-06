<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        // Default view kosong (hanya container #report-content)
        return view('admin.report.index');
    }

    public function show($category)
    {
        switch ($category) {
            case 'total_booking':
                $data = Booking::all();

                // Kira summary untuk chart
                $summary = [
                    'total'     => $data->count(),
                    'completed' => $data->where('bookingStatus','completed')->count(),
                    'pending'   => $data->where('bookingStatus','pending')->count(),
                    'cancelled' => $data->where('bookingStatus','cancelled')->count(),
                ];

                // Return partial view sahaja untuk AJAX inject
                return view('admin.report.partials.total_booking', compact('data','summary'));

            case 'revenue':
                $data = Booking::where('bookingStatus','completed')->sum('totalPrice');
                return view('admin.report.partials.revenue', compact('data'));

            case 'top_college':
                $data = User::select('college', DB::raw('COUNT(*) as total'))
                            ->groupBy('college')
                            ->orderByDesc('total')
                            ->get();
                return view('admin.report.partials.top_college', compact('data'));

            case 'blacklisted':
                $data = User::where('blacklisted',1)->get();
                return view('admin.report.partials.blacklisted', compact('data'));
        }
    }

    public function filterTotalBooking(Request $request)
    {
        $query = Booking::query();

        if ($request->month) {
            $query->whereMonth('pickup_dateTime', $request->month);
        }

        if ($request->year) {
            $query->whereYear('pickup_dateTime', $request->year);
        }

        $data = $query->get();

        // Kira summary untuk chart
        $summary = [
            'total'     => $data->count(),
            'completed' => $data->where('bookingStatus','completed')->count(),
            'pending'   => $data->where('bookingStatus','pending')->count(),
            'cancelled' => $data->where('bookingStatus','cancelled')->count(),
        ];

        // Return partial view sahaja
        return view('admin.report.partials.total_booking', compact('data','summary'));
    }
}
