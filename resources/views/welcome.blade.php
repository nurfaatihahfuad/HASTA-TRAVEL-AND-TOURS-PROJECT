@extends('layouts.app')

@section('content')

{{-- HERO SECTION --}}
<section class="hero d-flex flex-column justify-content-center align-items-center text-white text-center">
    <h1 class="display-4 fw-bold">Rent a vehicle with HastaTravel</h1>
    <p class="lead">Convenient vehicle rentals in UTM, Skudai</p>

    <form action="{{ route('vehicles.search') }}" method="GET" class="bg-white p-4 rounded shadow mt-4" style="max-width: 500px; width: 100%;">
        <div class="mb-3 text-start">
            <label for="pickup_dateTime" class="form-label fw-bold text-dark">Pick-up Date & Time</label>
            <input type="datetime-local" name="pickup_dateTime" class="form-control bg-white text-dark border-dark" required>
        </div>

        <div class="mb-3 text-start">
            <label for="return_dateTime" class="form-label fw-bold text-dark">Return Date & Time</label>
            <input type="datetime-local" name="return_dateTime" class="form-control bg-white text-dark border-dark" required>
        </div>

        <div class="text-center">
            <button type="submit" class="btn btn-danger">
                <i class="bi bi-search"></i> Search
            </button>
        </div>
    </form>


    <div class="mt-3 text-white text-center">
      <h4 class="display-5 fw-bold text-white" style="text-shadow: 2px 2px 5px rgba(0,0,0,0.7);">
        Vehicle Rental
      </h4>
    </div>

    <div class="d-flex justify-content-center" style="position:relative; center">
      <p class="mt-3 fs-5" style="text-shadow: 2px 2px 5px rgba(0,0,0,0.7);">
        Affordable Vehicles.<br>Unforgettable Trips.
      </p>
    </div>
</section>


{{-- CARS SECTION --}}
<section id="cars-section" class="py-5">
  <div class="container">
    <p class="text-center text-muted mb-0">The Cars</p>
    <h2 class="text-center fw-bold mb-5 display-6">Our Available Cars</h2>

    <div class="row g-4 justify-content-center">
      @foreach($vehicles as $vehicle)
        <div class="col-md-4 col-sm-6">
          <div class="card shadow-sm rounded-3 p-3">
            @if($vehicle->image_url)
              <img src="{{ asset('img/' . $vehicle->image_url) }}" class="card-img-top" alt="{{ $vehicle->vehicleName }}">
            @else
              <img src="{{ asset('img/default-car.png') }}" class="card-img-top" alt="Default Car">
            @endif
            <div class="card-body text-center">
              <h5 class="card-title fw-bold">{{ $vehicle->vehicleName }}</h5>
              <p class="card-text text-muted">Plate No: {{ $vehicle->plateNo }}</p>
              <p class="card-text text-muted">{{ $vehicle->description }}</p>

              <p class="card-text fw-bold">RM{{ $vehicle->price_per_day }}/hour</p>
              <a href="{{ route('booking.form', $vehicle->vehicleID) }}" class="btn btn-primary">Book Now</a>
            </div>
          </div>
        </div>
      @endforeach
    </div>

    <!-- View More button -->
    <div class="text-center mt-5">
      <a href="{{ route('browse.vehicle') }}" class="btn btn-lg btn-primary">View More</a>
    </div>
  </div>
</section>

@endsection
