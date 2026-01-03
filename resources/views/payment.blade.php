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

    <div class="payment-details-box">
    <div class="payment-card">
        <h1>Please Scan the QR Payment</h1>

        <div class="qr-section">
            <img src="{{ asset('img/payment.png') }}" class="qr-small" alt="QR Payment">
            <p>MALAYSIA NATIONAL QR</p>
        </div>

        <div class="payment-summary">
            <h3>Booking Summary</h3>
            <p>Car: {{ $vehicle['brand'] }} {{ $vehicle['model'] }} | Total Hours: {{ $totalHours }}</p>
            <p>Total Payment: RM{{ $totalPayment }}</p>
        </div>

        {{-- Form GET untuk pilih payment type dan paparkan amount --}}
        <form action="{{ route('payment.show') }}" method="GET">
            <div class="payment-row">
                <div class="payment-type">
                    <label>Choose Payment Type:</label>
                    <select name="paymentType" onchange="this.form.submit()">
                        <option value="Deposit" {{ $paymentType == 'Deposit' ? 'selected' : '' }}>Deposit Payment</option>
                        <option value="Full" {{ $paymentType == 'Full' ? 'selected' : '' }}>Full Payment</option>
                    </select>
                </div>

                <div class="payment-amount">
                    <label>Amount Should Pay (RM):</label>
                    <p class="amount-display">
                        @if($amountToPay !== null)
                            RM{{ $amountToPay }}
                        @else
                            -
                        @endif
                    </p>
                </div>
            </div>
            {{-- Jika tak mahu onchange (tanpa JS), buang onchange dan tambah butang ini:
            <button type="submit" class="submit-btn">Update</button>
            --}}
        </form>

        {{-- Form POST untuk submit payment proof + hantar pilihan/amount yang telah dikira --}}
        <form action="{{ route('payment.submit') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="paymentType" value="{{ $paymentType }}">
            <input type="hidden" name="amount" value="{{ $amountToPay }}">

            <label>Upload Payment Proof:</label>
            <input type="file" name="payment_proof" accept=".pdf,.jpeg,.jpg,.png" required>

            <div class="button-group">
                <button type="submit" class="submit-btn">Submit</button>
                <a href="/" class="back-btn">Back</a>
            </div>
        </form>
    </div>

</body>
</html>
