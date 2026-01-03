<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Payment</title>
    <!-- Corrected CSS path -->
    <link rel="stylesheet" href="{{ asset('css/payment.css') }}">
</head>
<body>

    <div class="payment-card">
        <h1 style="text-align: center;">Please Scan the QR Payment</h1>

        <div class="qr-section">
            <!-- Corrected image paths -->
            <img src="{{ asset('img/DuitNowLogo-1.jpg') }}" alt="DuitNow Logo" class="qr-logo">
            <img src="{{ asset('img/payment.png') }}" alt="DuitNow QR" class="qr-image">

            <p>MALAYSIA NATIONAL QR</p>
        </div>

        <form action="{{ route('payment.submit', $bookingID) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <label>UPLOAD PAYMENT PROOF IN PDF, JPEG OR PNG:</label>
            <input type="file" name="payment_proof" accept=".pdf,.jpeg,.jpg,.png" required>

            <h2>{{ $booking->vehicle->brand }} {{ $booking->vehicle->model }}</h2>
            <p>RM{{ $booking->vehicle->price_per_day }}/hour</p>
            <img src="{{ asset('img/' . $booking->vehicle->image_url) }}" alt="{{ $booking->vehicle->brand }}" class="car-image">

            <p>Total Hours: {{ $totalHours }} </p>
            <p>Total Payment: {{ $totalPayment }} </p>

            <div class="button-group">
                <button type="submit" class="submit-btn">Submit</button>
                <a href="/" class="back-btn">Back</a>
            </div>
        </form>
    </div>

</body>
</html>

