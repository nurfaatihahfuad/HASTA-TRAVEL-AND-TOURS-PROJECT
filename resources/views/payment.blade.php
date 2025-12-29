<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Payment</title>
    <link rel="stylesheet" href="/css/payment.css">
</head>
<body>

    <div class="payment-card">
        <h1 style="text-align: center;">Please Scan the QR Payment</h1>

        <div class="qr-section">
            <img src="{{ asset('img/payment.png') }}" class="qr-small" alt="QR Payment">
            <p>MALAYSIA NATIONAL QR</p>
        </div>

        <form action="{{ route('payment.submit', $bookingID) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <label>UPLOAD PAYMENT PROOF IN PDF, JPEG OR PNG:</label>
            
            <div class = "file-submit-row">
            <input type="file" name="payment_proof" accept=".pdf,.jpeg,.jpg,.png" required>
            <button type="submit" class="submit-btn">Submit</button>
            </div>

                <a href="/" class="back-btn">Back</a>
        </form>
    </div>

</body>
</html>
