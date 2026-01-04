<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book a Car</title>
    <link rel="stylesheet" href="{{ asset('css/booking.css') }}">
</head>
<body>

    

        <div class ="booking-container" >
        <div class="booking-content">
        <!-- Left: Booking Form -->
        <form action="{{ route('booking.store') }}" method="POST">
            @csrf

            <div class="page-header"> 
            <h2 class="text-3xl font-bold text-center">Book Your Vehicle</h2> 
            </div>

            <div>
                <input type="hidden" name="vehicleID" value="{{ $vehicle->vehicleID }}">
                <input type="hidden" name="pickup_dateTime" value="{{ $pickup_dateTime }}">
                <input type="hidden" name="return_dateTime" value="{{ $return_dateTime }}">
            </div>


            <div class="form-group">
                <label for="pickup_location">Pick-up Location: (Can be change)<span style="color:red">*</span></label>
                <input type="text" name="pickupAddress" value="UTM Mall" required>
            </div>

            <div class="form-group">
                <label for="return_location">Return Location: (Can be change)<span style="color:red">*</span></label>
                <input type="text" name="returnAddress" value="UTM Mall" required>
            </div>
    
            <div class="form-group">
                <label for="voucherCode">Voucher Code:</label>
                <input type="text" name="voucherCode">
            </div>

            <div class="button-group">
                <button type="submit" class="submit-btn">Submit</button>
            </div>
        </form>

        @if(isset($booking))
        <div class="booking-summary">
            <h3>Booking Confirmed!</h3>
                <p>Booking ID: {{ $booking->bookingID }}</p>
                <p>Vehicle: {{ $booking->vehicle->brand }} {{ $booking->vehicle->model }}</p>
                <p>Pickup: {{ $booking->pickup_dateTime }}</p>
                <p>Return: {{ $booking->return_dateTime }}</p>
            <a href="{{ route('payment.show', $booking->bookingID) }}" class="btn btn-success">
            Proceed to Payment
        </a>
    </div>
@endif
        <!-- Right: Car Info -->
        @if($vehicle)
        <div class="car-info">
            <h2>{{ $vehicle->vehicleName }}</h2> <!-- Ganti brand + model -->
            <p>RM{{ $vehicle->price_per_day }}/hour</p> <!-- Masih sama -->
            <img src="{{ asset('img/' . $vehicle->image_url) }}" alt="{{ $vehicle->vehicleName }}" class="car-image">

            <ul>
                <li>✅ Plate No: {{ $vehicle->plateNo }}</li>
                <li>✅ Year: {{ $vehicle->year }}</li>
                <li>✅ Description: {{ $vehicle->description }}</li>
                <li>❌ No smoking</li>
            </ul>
        </div>
        @endif
    </div>
</div>


</body>
</html>
