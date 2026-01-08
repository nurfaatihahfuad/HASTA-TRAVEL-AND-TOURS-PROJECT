<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Booking Summary</title>
    <link rel="stylesheet" href="{{ asset('css/bookingView.css') }}">
</head>
<body>
    <div class="summary-container">
        <h1>Booking Summary</h1>

        <div class="summary-card">
            <p><strong>Booking ID:</strong> {{ $booking->bookingID }}</p>
            <p><strong>Car:</strong> {{ $booking->vehicleName }} {{ $booking->plateNo }}</p>
            <p><strong>Pick-up Time:</strong> {{ \Carbon\Carbon::parse($booking->pickup_dateTime)->format('d M Y, h:i A') }}</p>
            <p><strong>Return Time:</strong> {{ \Carbon\Carbon::parse($booking->return_dateTime)->format('d M Y, h:i A') }}</p>
            <p><strong>Total Price:</strong> RM{{ number_format($totalPayment, 2) }}</p>
            <td>{{ ucfirst($booking->bookingStatus) }}</td>
        </div>

        <h2>Payment Details</h2>
        @if($payment)
            <p><strong>Payment ID:</strong> {{ $payment->paymentID }}</p>
            <p><strong>Payment Type:</strong> {{ $payment->paymentType }}</p>
            <p><strong>Amount Paid:</strong> RM{{ number_format($payment->amountPaid, 2) }}</p>
            <p><strong>Status:</strong> {{ ucfirst($payment->paymentStatus) }}</p>
            @if($payment->file_path)
                <p><strong>Proof File:</strong> 
                    <a href="{{ asset('storage/' . $payment->file_path) }}" target="_blank">View Proof</a>
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

        <button class="btn-print" onclick="window.print()">Print Receipt</button>
    </div>
</body>
</html>
