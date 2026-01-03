<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Payment</title>
    {{-- Link to your CSS file in public/css --}}
    <link rel="stylesheet" href="{{ asset('css/payment.css') }}">
</head>
<body>

    <div class="payment-card">
        <h1>Please Scan the QR Payment</h1>

        <div class="qr-section">
            <img src="{{ asset('img/payment.png') }}" class="qr-small" alt="QR Payment">
            <p>MALAYSIA NATIONAL QR</p>
        </div>

        <form action="{{ route('payment.submit', $bookingID) }}" method="POST" enctype="multipart/form-data">
            @csrf

        <div class="payment-row">
            <div class="payment-type">
                <label>Choose Payment Type:</label>
                <select name="paymentType" required>
                    <option value="Deposit">Deposit Payment</option>
                    <option value="Full">Full Payment</option>
            </select>
            </div>

            <div class="payment-amount">
                <label>Amount Should Paid (RM):</label>
                <input type="number" name="amount" step="0.01" required>
            </div>
        </div>

            <label>UPLOAD PAYMENT PROOF IN PDF, JPEG OR PNG:</label>
            <input type="file" name="payment_proof" accept=".pdf,.jpeg,.jpg,.png" required>

            <div class="button-group">
                <button type="submit" class="submit-btn">Submit</button>
                <a href="/" class="back-btn">Back</a>
            </div>
        </form>

        <div class="payment-summary">
            <h3>Booking Summary</h3>
            <p>Total Hours: {{ $totalHours }}</p>
            <p>Total Payment: RM{{ $totalPayment }}</p>
        </div>  

        <div class="payment-summary">
        <h3>Booking Summary</h3>
        <p>Total Hours: {{ $totalHours }}</p>
        <p>Total Payment: RM{{ $totalPayment }}</p>
        </div>

        <h2>Booking Summary</h2>
            <p>Car: {{ $vehicle->brand }} {{ $vehicle->model }}</p>
            <p>Total Hours: {{ $totalHours }}</p>
            <p>Total Payment: RM{{ $totalPayment }}</p>

            <form action="{{ route('payment.submit') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Payment fields -->
                <label>Payment Type:</label>
                <select name="payment_type" required>
                    <option value="Deposit">Deposit</option>
                    <option value="Full">Full Payment</option>
                </select>

                <label>Upload Proof:</label>
                <input type="file" name="payment_proof" required>

                <!-- Hidden booking fields -->
                <input type="hidden" name="pickup_dateTime" value="{{ $bookingData['pickup_dateTime'] }}">
                <input type="hidden" name="return_dateTime" value="{{ $bookingData['return_dateTime'] }}">
                <input type="hidden" name="pickupAddress" value="{{ $bookingData['pickupAddress'] }}">
                <input type="hidden" name="returnAddress" value="{{ $bookingData['returnAddress'] }}">
                <input type="hidden" name="vehicle_id" value="{{ $vehicle->id }}">
                <input type="hidden" name="quantity" value="{{ $bookingData['quantity'] }}">
                <input type="hidden" name="totalPayment" value="{{ $totalPayment }}">

                <button type="submit">Submit Payment</button>
            </form>


    </div>

</body>
</html>