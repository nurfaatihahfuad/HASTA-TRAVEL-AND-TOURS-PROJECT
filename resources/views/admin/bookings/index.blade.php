@extends('layouts.it_admin')

@section('title', 'All Bookings')

@section('content')
<div class="container-fluid">

    <h4 class="mb-4">Booking Management</h4>

    {{-- Filters --}}
    <form method="GET" class="card p-3 mb-4">
        <div class="row g-3 align-items-end">

            <div class="col-md-3">
                <label>Status</label>
                <select name="status" class="form-select">
                    <option value="">All</option>
                    @foreach(['pending','successful','rejected','completed','cancelled'] as $status)
                        <option value="{{ $status }}"
                            @selected(request('status') === $status)>
                            {{ ucfirst($status) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <label>From</label>
                <input type="date" name="from" class="form-control"
                       value="{{ request('from') }}">
            </div>

            <div class="col-md-3">
                <label>To</label>
                <input type="date" name="to" class="form-control"
                       value="{{ request('to') }}">
            </div>

            <div class="col-md-3">
                <button class="btn btn-primary w-100">
                    <i class="fas fa-filter"></i> Apply
                </button>
            </div>

        </div>
    </form>

    {{-- Booking Table --}}
    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Booking ID</th>
                        <th>Customer</th>
                        <th>Vehicle</th>
                        <th>Pickup</th>
                        <th>Return</th>
                        <th>Status</th>
                        <th>Total (RM)</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bookings as $booking)
                        <tr>
                            <td>{{ $booking->bookingID }}</td>
                            <td>{{ $booking->user->name ?? '-' }}</td>
                            <td>{{ $booking->vehicle->vehicleName ?? '-' }}</td>
                            <td>{{ \Carbon\Carbon::parse($booking->pickup_dateTime)->format('d M Y H:i') }}</td>
                            <td>{{ \Carbon\Carbon::parse($booking->return_dateTime)->format('d M Y H:i') }}</td>
                            <td>
                                <span class="badge
                                    @switch($booking->bookingStatus)
                                        @case('pending') bg-secondary @break
                                        @case('successful') bg-info @break
                                        @case('rejected') bg-warning @break
                                        @case('completed') bg-success @break
                                        @case('cancelled') bg-danger @break
                                    @endswitch
                                ">
                                    {{ ucfirst($booking->bookingStatus) }}
                                </span>
                            </td>
                            <td>{{ number_format($booking->total_price, 2) }}</td>
                            <td class="text-end">
                                <a href="{{ route('admin.bookings.show', $booking->bookingID) }}"
                                   class="btn btn-sm btn-outline-primary">
                                    View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                No bookings found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-3">
            {{ $bookings->links() }}
        </div>
    </div>

</div>
@endsection
