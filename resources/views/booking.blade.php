<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book a Car</title>
    <link rel="stylesheet" href="{{ asset('css/booking.css') }}">
</head>
<body>

    <div class="booking-container">
    <h1>Book Your Car</h1>

    <div class="booking-content">
        <!-- Left: Booking Form -->
        <form action="{{ route('booking.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="pickup_dateTime">Pick-up Date & Time:</label>
                <input type="datetime-local" name="pickup_dateTime" required>
            </div>

            <div class="form-group">
                <label for="return_dateTime">Return Date & Time:</label>
                <input type="datetime-local" name="return_dateTime" required>
            </div>


            <div class="form-group">
                <label for="pickup_location">Pick-up Location:</label>
                <input type="text" name="pickupAddress" value="UTM Mall" required>
            </div>

            <div class="form-group">
                <label for="return_location">Return Location:</label>
                <input type="text" name="returnAddress" value="UTM Mall" required>
            </div>

            <div class="form-group">
                <label for="voucherCode">Voucher Code:</label>
                <input type="text" name="voucherCode">
            </div>

             @if(isset($vehicle))
                <input type="hidden" name="vehicle_id" value="{{ $vehicle->id }}">
            @endif

            <button type="submit" class="submit-btn">Submit</button>
        </form>

        <!-- Right: Car Info -->
        @if($vehicle)
    <div class="car-info">
        <h2>{{ $vehicle->brand }} {{ $vehicle->model }}</h2>
        <p>RM{{ $vehicle->price_per_day }}/hour</p>
        <img src="{{ asset('img/' . $vehicle->image_url) }}" alt="{{ $vehicle->brand }}" class="car-image">
        <ul>
            <li>✅ {{ $vehicle->seats }}-seater</li>
            <li>✅ {{ $vehicle->features }}</li>
            <li>❌ No smoking</li>
        </ul>
    </div>
@endif

</body>
</html>
