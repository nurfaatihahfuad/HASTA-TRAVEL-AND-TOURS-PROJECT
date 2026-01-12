@extends('layouts.customer')

@section('title', 'Booking History')

@push('styles')
<style>
    .card {
        border: none;
        border-radius: 14px;
        box-shadow: 0 6px 18px rgba(0,0,0,0.06);
    }

    .card-header {
        background: transparent;
        font-weight: 600;
        border-bottom: 1px solid #f1f1f1;
    }
</style>
@endpush

@section('content')
<div class="container">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>My Bookings</h4>

        <form method="GET" action="{{ route('customers.BookingHistory.index') }}" class="mb-4">
        <div class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label">From date</label>
                <input type="date" name="from" class="form-control"
                    value="{{ request('from') }}">
            </div>

            <div class="col-md-4">
                <label class="form-label">To date</label>
                <input type="date" name="to" class="form-control"
                    value="{{ request('to') }}">
            </div>

            <div class="col-md-4">
                <button class="btn btn-primary w-100">
                    <i class="fas fa-filter"></i> Search
                </button>
            </div>
        </div>
    </form>

    </div>

    <div class="row">
        @forelse($bookings as $booking)
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h6 class="fw-bold">{{ $booking->bookingID }}</h6>

                        <p class="mb-1">
                            <i class="fas fa-car me-1"></i>
                            {{ $booking->vehicle->vehicleName ?? 'Vehicle' }}
                        </p>

                        <small class="text-muted">
                            {{ \Carbon\Carbon::parse($booking->pickup_dateTime)->format('d M Y') }}
                            →
                            {{ \Carbon\Carbon::parse($booking->return_dateTime)->format('d M Y') }}
                        </small>

                        <div class="mt-2">
                            @php
                                $badge = match($booking->bookingStatus) {
                                    'pending' => 'bg-warning text-dark',
                                    'successful' => 'bg-success',
                                    'rejected' => 'bg-danger',
                                    default => 'bg-secondary'
                                };
                            @endphp

                            <span class="badge {{ $badge }}">
                                {{ ucfirst($booking->bookingStatus) }}
                            </span>
                        </div>
                    </div>

                    <div class="card-footer bg-transparent text-end">
                        <a href="{{ route('customers.BookingHistory.show', $booking) }}"
                           class="btn btn-sm btn-outline-danger">
                            View Details
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center text-muted py-5">
                <i class="fas fa-calendar-times fa-3x mb-3"></i>
                <p>No bookings found</p>
            </div>
        @endforelse
    </div>

    {{ $bookings->links() }}
</div>
@endsection
