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
            <p><strong>Customer Name:</strong> {{ $booking->user->name }}</p>
            <p><strong>User ID:</strong> {{ $booking->userID }}</p>
            <p><strong>Car:</strong> {{ $booking->vehicle->vehicleName }} ({{ $booking->vehicle->plateNo }})</p>
            <p><strong>Pick-up Time:</strong> {{ \Carbon\Carbon::parse($booking->pickup_dateTime)->format('d M Y, h:i A') }}</p>
            <p><strong>Return Time:</strong> {{ \Carbon\Carbon::parse($booking->return_dateTime)->format('d M Y, h:i A') }}</p>
            <p><strong>Total Price:</strong> RM{{ number_format($totalPayment, 2) }}</p>
            <p><strong>Status:</strong> {{ ucfirst($booking->bookingStatus) }}</p>
        </div>


        <h2>Payment Details</h2>
        @if($payment)
            <p><strong>Payment ID:</strong> {{ $payment->paymentID }}</p>
            <p><strong>Payment Type:</strong> {{ $payment->paymentType }}</p>
            <p><strong>Amount Paid:</strong> RM{{ number_format($payment->amountPaid, 2) }}</p>

            @if($payment->paymentType === 'Deposit Payment') 
            <div class="qr-section">
                <img src="{{ asset('img/payment.png') }}" alt="QR Code" width="150" height="150"> 
                <form action="{{ route('payment.uploadReceipt', $payment->paymentID) }}" method="POST" enctype="multipart/form-data"> 
                @csrf 
                <input type="file" name="receipt_file" required> 
                <button type="submit" class="btn btn-primary">Upload Receipt</button> 
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

        <!--<button class="btn-print" onclick="window.print()">Print Receipt</button> -->

        <br><h2>Booking Actions</h2>
        <p><em>Kindly fill in these forms when you pickup and return the car, thank you.</em></p>
        <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 10px;">
            
            <a href="{{ route('inspection.pickupInspection', $booking->bookingID) }}" class="btn btn-success" style="text-decoration: none; padding: 10px 20px; border-radius: 5px; color: white; background-color: #28a745;">
                Pickup
            </a>

            <a href="{{ route('inspection.returnInspection', $booking->bookingID) }}" class="btn btn-warning" style="text-decoration: none; padding: 10px 20px; border-radius: 5px; color: white; background-color: #ffc107;">
                Return
            </a>
        </div>

</body>
</html>
