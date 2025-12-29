<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Payment</title>
    <link rel="stylesheet" href="/css/my-payment.css">
</head>
<body>

    <div class="payment-card">
        <h1 style="text-align: center;">Please Scan the QR Payment</h1>

        <div class="qr-section">
            <img src="{{ asset('public/img/DuitNowLogo-1.jpg') }}" alt="DuitNow Logo" class="qr-section">
            <img src="{{ asset('img/payment.png') }}" alt="DuitNow QR" class="qr-image">
            <p>MALAYSIA NATIONAL QR</p>
        </div>

        <form action="{{ route('payment.submit', $bookingID) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <label>UPLOAD PAYMENT PROOF IN PDF, JPEG OR PNG:</label>
            <input type="file" name="payment_proof" accept=".pdf,.jpeg,.jpg,.png" required>

            <div class="button-group">
                <button type="submit" class="submit-btn">Submit</button>
                <a href="/" class="back-btn">Back</a>
            </div>
        </form>
    </div>

</body>
</html>
