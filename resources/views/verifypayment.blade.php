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
                    <td>{{ $payment->created_at ? \Carbon\Carbon::parse($payment->created_at)->format('d/m/Y') : '-' }}</td>
                    <td>{{ $payment->booking->user->email ?? '-' }}</td>
                    <td>
                        @if($payment->booking && $payment->booking->vehicle)
                            Car: {{ $payment->booking->vehicle->carModel ?? '-' }}<br>
                            Pickup: {{ $payment->booking->pickup_dateTime ?? '-' }}<br>
                            Return: {{ $payment->booking->return_dateTime ?? '-' }}
                        @else
                            Booking details not available
                        @endif
                    </td>
                    <td>{{ $payment->paymentType ?? '-' }}</td>
                    <td>RM{{ number_format($payment->amountPaid ?? 0, 2) }}</td>
                    <td>{{ $payment->verifiedBy ?? '-' }}</td>
                    <td>{{ $payment->updated_at ? \Carbon\Carbon::parse($payment->updated_at)->format('d/m/Y') : '-' }}</td>
                    <td>
                        <span class="status-badge status-{{ $payment->paymentStatus }}">
                            {{ $payment->paymentStatus }}
                        </span>
                    </td>
                    <td>
                        <form method="POST" action="{{ route('paymentverify.update', $payment->paymentID) }}">
                            @csrf
                            @method('PUT')
                            <button name="status" value="approved" class="btn btn-success">Approve</button>
                            <button name="status" value="rejected" class="btn btn-danger">Reject</button>
                        </form>
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