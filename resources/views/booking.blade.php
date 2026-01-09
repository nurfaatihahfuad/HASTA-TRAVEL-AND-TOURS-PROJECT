<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book a Car</title>
    <link rel="stylesheet" href="{{ asset('css/booking.css') }}">
</head>
<body>
    <div class="page-header">
        <h2 class="text-3xl font-bold text-center">Book Your Vehicle</h2>
    </div>
    
    <div class="booking-container">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="booking-content">
            <!-- Left Column: Booking Form -->
            <div class="booking-form">
                <form action="{{ route('booking.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="vehicleID" value="{{ $vehicle->vehicleID }}">

                    <!-- Pickup DateTime (read-only) -->
                    <div class="form-group">
                        <label for="pickup_dateTime">Pick-up Date & Time<span style="color:red">*</span></label>
                        <input type="text" 
                            value="{{ \Carbon\Carbon::parse($pickup_dateTime)->format('d M Y, h:i A') }}" 
                            readonly class="readonly-field">
                        <input type="hidden" name="pickup_dateTime" value="{{ $pickup_dateTime }}">
                    </div>

                    <!-- Return DateTime (read-only) -->
                    <div class="form-group">
                        <label for="return_dateTime">Return Date & Time<span style="color:red">*</span></label>
                        <input type="text" 
                            value="{{ \Carbon\Carbon::parse($return_dateTime)->format('d M Y, h:i A') }}" 
                            readonly class="readonly-field">
                        <input type="hidden" name="return_dateTime" value="{{ $return_dateTime }}">
                    </div>

                    <!-- Pickup Location Dropdown -->
                    <div class="form-group">
                        <label for="pickup_location">Pick-up Location<span style="color:red">*</span></label>
                        <select name="pickupAddress" id="pickup_location" class="form-control" required>
                            <option value="kolej_9_10">Kolej 9 & 10</option>
                            <option value="kolej_dato_onn">Kolej Dato Onn Jaafar</option>
                            <option value="kolej_endon">Kolej Datin Seri Endon</option>
                            <option value="kolej_perdana">Kolej Perdana</option>
                            <option value="kolej_rahman_putra">Kolej Rahman Putra</option>
                            <option value="kolej_tuanku_canselor">Kolej Tuanku Canselor</option>
                            <option value="kolej_tun_dr_ismail">Kolej Tun Dr Ismail</option>
                            <option value="kolej_tun_fatimah">Kolej Tun Fatimah</option>
                            <option value="kolej_tun_hussein_on">Kolej Tun Hussein On</option>
                            <option value="kolej_tun_razak">Kolej Tun Razak</option>
                            <option value="faculty_bes">Faculty of Built Environment and Surveying</option>
                            <option value="faculty_ai">Faculty of Artificial Intelligence</option>
                            <option value="faculty_computing">Faculty of Computing</option>
                            <option value="faculty_electrical">Faculty of Electrical Engineering</option>
                            <option value="faculty_education">Faculty of Educational Sciences and Technology</option>
                            <option value="faculty_civil">Faculty of Civil Engineering</option>
                            <option value="faculty_mechanical">Faculty of Mechanical Engineering</option>
                            <option value="faculty_chemical">Faculty of Chemical & Energy Engineering</option>
                            <option value="faculty_management">Faculty of Management</option>
                            <option value="faculty_science">Faculty of Science</option>
                            <option value="faculty_social">Faculty of Social Sciences and Humanities</option>
                            <option value="others">Others</option>
                        </select>
                    </div>

                    <!-- Pickup Others input -->
                    <div class="form-group" id="pickup_others" style="display:none;">
                        <label for="pickup_other_location">Please specify pick-up location:</label>
                        <input type="text" name="pickup_other_location" class="form-control">
                    </div>

                    <!-- Return Location Dropdown -->
                    <div class="form-group">
                        <label for="return_location">Return Location<span style="color:red">*</span></label>
                        <select name="returnAddress" id="return_location" class="form-control" required>
                            <option value="kolej_9_10">Kolej 9 & 10</option>
                            <option value="kolej_dato_onn">Kolej Dato Onn Jaafar</option>
                            <option value="kolej_endon">Kolej Datin Seri Endon</option>
                            <option value="kolej_perdana">Kolej Perdana</option>
                            <option value="kolej_rahman_putra">Kolej Rahman Putra</option>
                            <option value="kolej_tuanku_canselor">Kolej Tuanku Canselor</option>
                            <option value="kolej_tun_dr_ismail">Kolej Tun Dr Ismail</option>
                            <option value="kolej_tun_fatimah">Kolej Tun Fatimah</option>
                            <option value="kolej_tun_hussein_on">Kolej Tun Hussein On</option>
                            <option value="kolej_tun_razak">Kolej Tun Razak</option>
                            <option value="faculty_bes">Faculty of Built Environment and Surveying</option>
                            <option value="faculty_ai">Faculty of Artificial Intelligence</option>
                            <option value="faculty_computing">Faculty of Computing</option>
                            <option value="faculty_electrical">Faculty of Electrical Engineering</option>
                            <option value="faculty_education">Faculty of Educational Sciences and Technology</option>
                            <option value="faculty_civil">Faculty of Civil Engineering</option>
                            <option value="faculty_mechanical">Faculty of Mechanical Engineering</option>
                            <option value="faculty_chemical">Faculty of Chemical & Energy Engineering</option>
                            <option value="faculty_management">Faculty of Management</option>
                            <option value="faculty_science">Faculty of Science</option>
                            <option value="faculty_social">Faculty of Social Sciences and Humanities</option>
                            <option value="others">Others</option>
                        </select>
                    </div>

                    <!-- Return Others input -->
                    <div class="form-group" id="return_others" style="display:none;">
                        <label for="return_other_location">Please specify return location:</label>
                        <input type="text" name="return_other_location" class="form-control">
                    </div>

                    <!-- Voucher -->
                    <div class="form-group">
                        <label for="voucherCode">Voucher Code:</label>
                        <input type="text" name="voucherCode">
                    </div>

                    <!-- Submit button -->
                    <div class="button-group">
                        <button type="submit" class="submit-btn">Submit Booking</button>
                    </div>
                </form>
            </div>

            <!-- Right Column: Vehicle Details -->
            @if($vehicle)
                <div class="car-info">
                    <h2>{{ $vehicle->vehicleName }}</h2>
                    <p class="price">RM{{ $vehicle->price_per_day }}/hour</p>
                    <img src="{{ asset('img/' . $vehicle->image_url) }}" alt="{{ $vehicle->vehicleName }}" class="car-image">
                    
                    <div class="vehicle-details">
                        <h3>Vehicle Details</h3>
                        <ul>
                            <li>✅ Plate No: {{ $vehicle->plateNo }}</li>
                            <li>✅ Year: {{ $vehicle->year }}</li>
                            <li>✅ Description: {{ $vehicle->description }}</li>
                            <li>❌ No smoking allowed</li>
                        </ul>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <script>
        // Toggle pickup others input
        document.getElementById('pickup_location').addEventListener('change', function() {
            if (this.value === 'others') {
                document.getElementById('pickup_others').style.display = 'block';
            } else {
                document.getElementById('pickup_others').style.display = 'none';
            }
        });

        // Toggle return others input
        document.getElementById('return_location').addEventListener('change', function() {
            if (this.value === 'others') {
                document.getElementById('return_others').style.display = 'block';
            } else {
                document.getElementById('return_others').style.display = 'none';
            }
        });
    </script>
</body>
</html>