<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Payment</title>
    <link rel="stylesheet" href="{{ asset('css/payment.css') }}">
</head>
<body>

    <div class="payment-details-box">
    <div class="payment-card">
        <h1>Please Scan the QR Payment</h1>

        <div class="qr-section">
            <img src="{{ asset('img/payment.png') }}" class="qr-small" alt="QR Payment">
            <p>MALAYSIA NATIONAL QR</p>
        
            <h3>Booking Summary</h3>
            <p>Car: {{ $booking->vehicle->vehicleName ?? 'N/A' }} | Total Hours: {{ $totalHours ?? '0' }}</p>
            <p>Total Payment: RM{{ number_format($totalAmount ?? 0, 2) }}</p>
        </div>

        {{-- Form GET untuk pilih payment type --}}
        <form action="{{ route('payment.show', ['bookingID' => $booking->bookingID]) }}" method="GET">
            <div class="payment-row">
                <div class="payment-type">
                    <label>Choose Payment Type:</label>
                    <select name="paymentType" onchange="this.form.submit()">
                        <option value="Deposit Payment" {{ ($paymentType ?? 'Deposit Payment') == 'Deposit Payment' ? 'selected' : '' }}>
                            Deposit Payment (RM50)
                        </option>
                        <option value="Full Payment" {{ ($paymentType ?? '') == 'Full Payment' ? 'selected' : '' }}>
                            Full Payment (RM{{ number_format($totalAmount ?? 0, 2) }})
                        </option>
                    </select>
                </div>

                <div class="payment-amount">
                    <label>Amount Should Pay (RM):</label>
                    <p class="amount-display">
                        @if(isset($amountToPay) && $amountToPay > 0)
                            <strong style="font-size: 24px; color: #28a745;">
                                RM{{ number_format($amountToPay, 2) }}
                            </strong>
                        @else
                            <span style="color: #dc3545;">-</span>
                        @endif
                    </p>
                </div>
            </div>
        </form>

        {{-- Form POST untuk submit payment proof --}}
        @if(isset($amountToPay) && $amountToPay > 0)
        <form action="{{ route('payment.submit', ['bookingID' => $booking->bookingID]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="bookingID" value="{{ $booking->bookingID }}">
            <input type="hidden" name="paymentType" value="{{ $paymentType }}">
            <input type="hidden" name="amountPaid" value="{{ $amountToPay }}">

            <label>Upload Payment Proof:</label>
            <input type="file" name="payment_proof" accept=".pdf,.jpeg,.jpg,.png" required>

            <div class="button-group">
                <button type="submit" class="submit-btn">Submit Payment</button>
                <a href="{{ route('booking.form', ['vehicleID' => $booking->vehicleID]) }}" 
                    class="back-btn">
                    <i class="fas fa-arrow-left"></i> Back to Booking Form
                </a>
            </div>
        </form>
        @else
        <div class="alert alert-warning">
            Please select a payment type to proceed with payment.
        </div>
        @endif
    </div>
    </div>

    {{-- Debug info (optional) --}}
    <div style="position: fixed; bottom: 10px; right: 10px; background: #f8f9fa; padding: 10px; border-radius: 5px; font-size: 12px; display: none;">
        <strong>Debug:</strong><br>
        Total Hours: {{ $totalHours }}<br>
        Price/Hour: {{ $booking->vehicle->price_per_hour ?? 0 }}<br>
        Total Amount: {{ $totalAmount }}<br>
        Payment Type: {{ $paymentType }}<br>
        Amount to Pay: {{ $amountToPay }}
    </div>

</body>
</html>