@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/verify.css') }}">

<div class="container">
    <h2>Pending Payment Verification</h2>
    
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @if($payments->count() > 0)
        <table class="verify-table">
            <thead>
                <tr>
                    <th>Payment ID</th>
                    <th>Date</th>
                    <th>Email</th>
                    <th>Booking Details</th>
                    <th>Payment Type</th>
                    <th>Amount</th>
                    <th>Verified By</th>
                    <th>Verified Date</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($payments as $payment)
                <tr>
                    <td>{{ $payment->paymentID }}</td>
                    <td>{{ $payment->created_at ? \Carbon\Carbon::parse($payment->created_at)->format('d/m/Y H:i') : '-' }}</td>
                    <td>{{ $payment->booking->user->email ?? '-' }}</td>
                    <td>
                        @if($payment->booking && $payment->booking->vehicle)
                            <strong>Booking ID:</strong> {{ $payment->booking->bookingID }}<br>
                            <strong>Car:</strong> {{ $payment->booking->vehicle->carModel ?? '-' }}<br>
                            <strong>Pickup:</strong> {{ \Carbon\Carbon::parse($payment->booking->pickup_dateTime)->format('d/m/Y H:i') }}<br>
                            <strong>Return:</strong> {{ \Carbon\Carbon::parse($payment->booking->return_dateTime)->format('d/m/Y H:i') }}
                        @else
                            <span class="text-danger">Booking details not available</span>
                        @endif
                    </td>
                    <td>{{ $payment->paymentType ?? '-' }}</td>
                    <td>RM{{ number_format($payment->amountPaid ?? 0, 2) }}</td>
                    <td>{{ $payment->verifiedBy ?? '-' }}</td>
                    <td>{{ $payment->verified_at ? \Carbon\Carbon::parse($payment->verified_at)->format('d/m/Y H:i') : '-' }}</td>
                    <td>
                        <span class="status-badge status-{{ $payment->paymentStatus }}">
                            {{ ucfirst($payment->paymentStatus) }}
                        </span>
                    </td>
                    <td>
                        @if($payment->booking)
                            <!-- Form untuk APPROVE -->
                            <form action="{{ route('booking.updateStatus', $payment->booking->bookingID) }}" 
                                  method="POST" 
                                  style="display:inline-block; margin-right:5px;">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="successful">
                                <button type="submit" 
                                        class="btn btn-success btn-sm"
                                        onclick="return confirm('Approve this booking?')">
                                    Approve
                                </button>
                            </form>

                            <!-- Form untuk REJECT -->
                            <form action="{{ route('booking.updateStatus', $payment->booking->bookingID) }}" 
                                  method="POST" 
                                  style="display:inline-block;">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="rejected">
                                <button type="submit" 
                                        class="btn btn-danger btn-sm"
                                        onclick="return confirm('Reject this booking?')">
                                    Reject
                                </button>
                            </form>
                        @else
                            <span class="text-muted">No booking found</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="alert alert-info">
            No pending payments found.
        </div>
    @endif
</div>
@endsection