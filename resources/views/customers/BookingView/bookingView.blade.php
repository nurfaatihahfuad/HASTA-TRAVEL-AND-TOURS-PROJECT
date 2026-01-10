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

        <!--<button class="btn-print" onclick="window.print()">Print Receipt</button> -->

        <br><h2>Booking Actions</h2>
        <p><em>Kindly fill in these forms when you pickup and return the car, thank you.</em></p>
        <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 10px;">
            <form action="{{ route('inspection.pickupInspection', $booking->bookingID) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-success">Pickup</button>
            </form>

            <form action="{{ route('inspection.returnInspection', $booking->bookingID) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-warning">Return</button>
            </form>
        </div>

        <h3>Inspection Records</h3>
        @if($booking->inspections->isEmpty())
            <p>No inspection records yet.</p>
        @else
            <table class="table">
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>Condition</th>
                        <th>Mileage</th>
                        <th>Fuel</th>
                        <th>Damage</th>
                        <th>Remark</th>
                        <th>Fuel Evidence</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($booking->inspections as $insp)
                        <tr>
                            <td>{{ ucfirst($insp->inspectionType ?? 'general') }}</td>
                            <td>{{ $insp->carCondition ?? '-' }}</td>
                            <td>{{ $insp->mileageReturned ?? '-' }}</td>
                            <td>{{ $insp->fuelLevel !== null ? $insp->fuelLevel . '%' : '-' }}</td>
                            <td>{{ $insp->damageDetected === 1 ? 'Yes' : ($insp->damageDetected === 0 ? 'No' : '-') }}</td>
                            <td>{{ $insp->remark ?? '-' }}</td>
                            <td>
                                @if($insp->fuel_evidence)
                                    <a href="{{ asset('storage/' . $insp->fuel_evidence) }}" target="_blank">View</a>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
</body>
</html>
