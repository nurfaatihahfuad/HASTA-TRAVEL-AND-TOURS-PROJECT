@extends('layouts.admin')

@section('title', 'Booking Details')

@section('content')
<div class="container-fluid">

    <a href="{{ route('admin.bookings.index') }}" class="btn btn-outline-secondary mb-3">
        <i class="fas fa-arrow-left"></i> Back to Bookings
    </a>

    <div class="row g-4">

        {{-- Booking & Customer Info --}}
        <div class="col-md-6">
            <div class="card p-3">
                <h5 class="mb-3">Booking Information</h5>

                <p><strong>Booking ID:</strong> {{ $booking->bookingID }}</p>
                <p><strong>Status:</strong> 
                    <span class="badge 
                        @switch($booking->bookingStatus)
                            @case('pending') bg-secondary @break
                            @case('approved') bg-info @break
                            @case('ongoing') bg-warning @break
                            @case('completed') bg-success @break
                            @case('cancelled') bg-danger @break
                        @endswitch">
                        {{ ucfirst($booking->bookingStatus) }}
                    </span>
                </p>
                <p><strong>Pickup:</strong> {{ \Carbon\Carbon::parse($booking->pickup_dateTime)->format('d M Y H:i') }}</p>
                <p><strong>Return:</strong> {{ \Carbon\Carbon::parse($booking->return_dateTime)->format('d M Y H:i') }}</p>
                <p><strong>Pickup Address:</strong> {{ $booking->pickupAddress }}</p>
                <p><strong>Return Address:</strong> {{ $booking->returnAddress }}</p>
                <p><strong>Voucher:</strong> {{ $booking->voucherCode ?? '-' }}</p>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card p-3">
                <h5 class="mb-3">Customer Information</h5>
                <p><strong>Name:</strong> {{ $booking->user->name ?? '-' }}</p>
                <p><strong>Email:</strong> {{ $booking->user->email ?? '-' }}</p>
                <p><strong>Phone:</strong> {{ $booking->user->phone ?? '-' }}</p>
            </div>
        </div>

    </div>

    {{-- Vehicle Info --}}
    <div class="row g-4 mt-3">
        <div class="col-md-12">
            <div class="card p-3">
                <h5 class="mb-3">Vehicle Information</h5>
                <p><strong>Name:</strong> {{ $booking->vehicle->vehicleName ?? '-' }}</p>
                <p><strong>Type:</strong> {{ $booking->vehicle->type ?? '-' }}</p>
                <p><strong>Price per Hour:</strong> RM {{ number_format($booking->vehicle->price_per_hour ?? 0, 2) }}</p>
                <p><strong>Price per Day:</strong> RM {{ number_format($booking->vehicle->price_per_day ?? 0, 2) }}</p>
            </div>
        </div>
    </div>

    {{-- Payment Breakdown --}}
    <div class="row g-4 mt-3">
        <div class="col-md-12">
            <div class="card p-3">
                <h5 class="mb-3">Payment</h5>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Payment ID</th>
                            <th>Amount (RM)</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($booking->payments as $payment)
                        <tr>
                            <td>{{ $payment->paymentID }}</td>
                            <td>{{ number_format($payment->amount, 2) }}</td>
                            <td>
                                <span class="badge 
                                    @switch($payment->status)
                                        @case('pending') bg-warning @break
                                        @case('approved') bg-success @break
                                        @case('rejected') bg-danger @break
                                    @endswitch">
                                    {{ ucfirst($payment->status) }}
                                </span>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($payment->created_at)->format('d M Y H:i') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">No payments recorded</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Inspection Details --}}
    <div class="row g-4 mt-3">
        <div class="col-md-12">
            <div class="card p-3">
                <h5 class="mb-3">Inspections</h5>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Inspection Type</th>
                            <th>Date</th>
                            <th>Staff</th>
                            <th>Notes</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($booking->inspections as $inspection)
                        <tr>
                            <td>{{ ucfirst($inspection->inspectionType) }}</td>
                            <td>{{ \Carbon\Carbon::parse($inspection->created_at)->format('d M Y H:i') }}</td>
                            <td>{{ $inspection->staff->name ?? '-' }}</td>
                            <td>{{ $inspection->notes ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">No inspections recorded</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection
