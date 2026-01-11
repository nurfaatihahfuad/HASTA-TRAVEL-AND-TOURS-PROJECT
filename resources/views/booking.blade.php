<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book a Car</title>
    <link rel="stylesheet" href="{{ asset('css/booking.css') }}">
    <!-- SweetAlert2 CSS (Optional) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
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
                <form id="bookingForm" action="{{ route('booking.store') }}" method="POST">
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
                        <input type="text" name="pickup_other_location" id="pickup_other_location" class="form-control">
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
                        <input type="text" name="return_other_location" id="return_other_location" class="form-control">
                    </div>

                    <!-- Voucher -->
                    <div class="form-group">
                        <label for="voucherCode">Voucher Code:</label>
                        <input type="text" name="voucherCode" id="voucherCode">
                    </div>

                    <!-- Submit button -->
                    <div class="button-group">
                        <button type="submit" id="submitBookingBtn" class="submit-btn">Submit Booking</button>

                        <button type="button" id="backBtn" class="submit-btn">Back</button>
                    </div>

                    <script>
                        document.getElementById('backBtn').addEventListener('click', function() {
                            // Try to go back in history
                            if (window.history.length > 1) {
                                window.history.back();
                            } else {
                                // If no history, go to vehicles page
                                window.location.href = "/vehicles"; // Adjust this URL
                            }
                        });
                    </script>

                   
                </form>
            </div>

            <!-- Right Column: Vehicle Details -->
            @if($vehicle)
                <div class="car-info">
                    <h2>{{ $vehicle->vehicleName }}</h2>
                    <p class="price">RM{{ $vehicle->price_per_day }}/day</p>
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

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Toggle pickup others input
        document.getElementById('pickup_location').addEventListener('change', function() {
            const pickupOthersDiv = document.getElementById('pickup_others');
            const pickupOtherInput = document.getElementById('pickup_other_location');
            
            if (this.value === 'others') {
                pickupOthersDiv.style.display = 'block';
                pickupOtherInput.required = true;
            } else {
                pickupOthersDiv.style.display = 'none';
                pickupOtherInput.required = false;
                pickupOtherInput.value = '';
            }
        });

        // Toggle return others input
        document.getElementById('return_location').addEventListener('change', function() {
            const returnOthersDiv = document.getElementById('return_others');
            const returnOtherInput = document.getElementById('return_other_location');
            
            if (this.value === 'others') {
                returnOthersDiv.style.display = 'block';
                returnOtherInput.required = true;
            } else {
                returnOthersDiv.style.display = 'none';
                returnOtherInput.required = false;
                returnOtherInput.value = '';
            }
        });

        // Booking submission with popup
        document.getElementById('submitBookingBtn').addEventListener('click', function(e) {
            e.preventDefault();
            
            // Get form values
            const pickupLocation = document.getElementById('pickup_location').value;
            const returnLocation = document.getElementById('return_location').value;
            const pickupOther = document.getElementById('pickup_other_location').value;
            const returnOther = document.getElementById('return_other_location').value;
            
            // Validate required fields
            if (!pickupLocation || !returnLocation) {
                Swal.fire({
                    icon: 'error',
                    title: 'Validation Error',
                    text: 'Please select both pick-up and return locations!',
                });
                return;
            }
            
            if (pickupLocation === 'others' && !pickupOther.trim()) {
                Swal.fire({
                    icon: 'error',
                    title: 'Validation Error',
                    text: 'Please specify your pick-up location!',
                });
                return;
            }
            
            if (returnLocation === 'others' && !returnOther.trim()) {
                Swal.fire({
                    icon: 'error',
                    title: 'Validation Error',
                    text: 'Please specify your return location!',
                });
                return;
            }
            
            // Get location display names
            function getLocationDisplayName(value, otherValue) {
                if (value === 'others') {
                    return otherValue;
                }
                
                const select = document.getElementById('pickup_location');
                const option = select.querySelector(`option[value="${value}"]`);
                return option ? option.textContent : value;
            }
            
            const pickupDisplay = getLocationDisplayName(pickupLocation, pickupOther);
            const returnDisplay = getLocationDisplayName(returnLocation, returnOther);
            
            // Show confirmation popup
            Swal.fire({
                title: 'Confirm Booking?',
                html: `
                    <div class="text-start">
                        <p><strong>Vehicle:</strong> {{ $vehicle->vehicleName }}</p>
                        <p><strong>Pick-up:</strong> {{ \Carbon\Carbon::parse($pickup_dateTime)->format('d M Y, h:i A') }}</p>
                        <p><strong>Return:</strong> {{ \Carbon\Carbon::parse($return_dateTime)->format('d M Y, h:i A') }}</p>
                        <p><strong>Pick-up Location:</strong> ${pickupDisplay}</p>
                        <p><strong>Return Location:</strong> ${returnDisplay}</p>
                        <p><strong>Price per day:</strong> RM{{ $vehicle->price_per_day }}</p>
                    </div>
                `,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Book Now!',
                cancelButtonText: 'Cancel',
                reverseButtons: true,
                width: '600px'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Submit form
                    document.getElementById('bookingForm').submit();
                }
            });
        });

        // Optional: Success message popup if redirected with success session
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Booking Successful!',
                text: '{{ session('success') }}',
                timer: 3000,
                showConfirmButton: false
            });
        @endif
    </script>
</body>
</html>