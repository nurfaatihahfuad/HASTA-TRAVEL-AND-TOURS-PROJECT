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
                    <option value="Deposit">Deposit</option>
                    <option value="Full">Full Payment</option>
            </select>
            </div>

            <div class="payment-amount">
                <label>Enter Amount (RM):</label>
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

        
    </div>

</body>
</html>