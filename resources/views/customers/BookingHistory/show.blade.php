@extends('layouts.customer')

@section('title', 'Booking Details')

@section('content')
<link rel="stylesheet" href="{{ asset('css/bookingView.css') }}">
<div class="container">

    <a href="{{ route('customers.BookingHistory.show',$booking->bookingID) }}"
       class="btn btn-sm btn-outline-secondary mb-3">
        ← Back
    </a>

    <div class="card mb-4">
        <div class="card-header fw-bold">
            Booking {{ $booking->bookingID }}
        </div>

        <div class="card-body row">
            <div class="col-md-6">
                <h6>Vehicle Details</h6>
                <p>{{ $booking->vehicle->vehicleName }}</p>
                <p>Plate: {{ $booking->vehicle->plateNo ?? '-' }}</p>
            </div>

            <div class="col-md-6">
                <h6>Booking Details</h6>
                <p>Pickup: {{ \Carbon\Carbon::parse($booking->pickup_dateTime)->format('d M Y, h:i A') }}</p>
                <p>Return: {{ \Carbon\Carbon::parse($booking->return_dateTime)->format('d M Y, h:i A') }}</p>
                <p>Total Hours: {{ ($totalHours)}}</p>
                <p>Total Price: RM {{ number_format($totalPayment, 2) }}</p>
            </div>
        </div>
    </div>

    {{-- PAYMENT --}}
    <div class="card mb-4">
        <div class="card-header fw-bold">
            Payment Breakdown
        </div>
        <div class="card-body">
            @if($payment)
                <p><strong>Payment Type:</strong> {{ $payment->paymentType }}</p>
                <p><strong>Amount Paid:</strong> RM{{ number_format($payment->amountPaid, 2) }}</p>

                @if($payment->paymentType === 'Deposit Payment') 
                <div class="qr-section">
                    <img src="{{ asset('img/payment.png') }}" alt="QR Code" width="150" height="150"> 
                    <form action="{{ route('payment.uploadReceipt', $payment->paymentID) }}" method="POST" enctype="multipart/form-data"> 
                    @csrf 
                    <input type="file" name="receipt_file" required> 
                    <button type="submit" class="btn btn-primary" >Upload Receipt</button> 
                </form> 
                </div> 
                @endif

                @if($payment->receipt_file_path)
                    <p><strong>Proof File:</strong> 
                        <a href="{{ asset('storage/' . $payment->receipt_file_path) }}" target="_blank">View Proof</a>
                    </p>
                @endif
            @else
                <p><em>No payment record found for this booking.</em></p>
            @endif

            @if(session('success'))
                <div class="alert-success">
                    {{ session('success') }}
                </div>
            @endif
        </div>
    </div>

    {{-- ACTIONS --}}
    <div class="mb-4">
        <h4>Booking Actions</h4>
        <p><em>Kindly fill in these forms when you pickup and return the car, thank you.</em></p>
        <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 10px;">
            
            <a href="{{ route('inspection.pickupInspection', $booking->bookingID) }}" class="btn btn-success" style="text-decoration: none; padding: 10px 20px; border-radius: 5px; color: white; background-color: #28a745;">
                Pickup
            </a>

            <a href="{{ route('inspection.returnInspection', $booking->bookingID) }}" class="btn btn-warning" style="text-decoration: none; padding: 10px 20px; border-radius: 5px; color: white; background-color: #ffc107;">
                Return
            </a>
        </div>

</div>
@endsection
