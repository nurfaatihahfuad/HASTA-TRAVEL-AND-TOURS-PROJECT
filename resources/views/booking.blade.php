<!-- Tambah Account bank dgn jenis bank dalam booking,
     Total price yg dh sewa
     Owner kereta diperlukan utk setiap kereta
     Kena masukkan gambar kereta awal ii
     Buat drop down list: office, college, faculty, others
     Delivery: office free, faculty: 15, college: 10
     nombor account: nombor dgn jenis bank, tak nak sengkang, deposit 50, full campur total terus dgn deposit
     swap kalau tak available kereta
     Kena letak loceng
     BookingID- BOLEH TEKAN TGK DETAIL KERETA, NAMA CUSTOMER, KERETA, PICKUP, RETURN
     ADMIN BOLEH VIEW SEMUA DASHBOARD

     TAMBAH OTHERS BOLEH INPUT LAGI
     TAMBAH EDIT GAMBAR
     VEHCILE NAME, NOM PLATE
     DELETE TAK LEH RETIREVE
     EDIT JER LAH

     Masukkan sekali car yg dh booking
     vehicle, tuh inspection tuh ada return, pickup
     crud tuh dekat customer
     booking tuh buat dalam view
     runner tak yah dashboard
     salesperson, masuk dalam dashboard 
     kereta apa, nama customer, total rm, pickup, unpaid = deposit, booking, paid = merah
     rental aggreement, bawah ada upload rental aggreement download then upload yg dh tandatangan
     dia kena generate sekali time dh booking
     commission dalam extra jon buat luar waktu kerja

     customer dgn masuk dalam booking
     deposit, 1 bar RM10 - finance check return car deposit
     hold bagi reason and kalau deposit tuh kena pulangkan balik
     customer boleh terus access

     dashboard staff: view: car available, date dgn masa
     staff maintnance: nak check car availability
     nak tahu kereta tuh 
     all booking kena buat tuh
     staff access tuh boleh check yg mcm check limit minyak supaya nak tahu pasal deposit ke tak mcm tuh?

    kena cuba jugak
    
    Dashboard gabung jer semua then nanti boleh filter jer 
    kalau ada delete tuh dia mcm boleh kena retrieve
    Mana ii button boleh tekan
    Hah tuh jer lah
    guna email student@graduate.utm
 -->






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
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
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
