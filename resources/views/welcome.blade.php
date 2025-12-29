@extends('layouts.app')

@section('content')

{{-- HERO SECTION --}}
<section class="hero d-flex flex-column justify-content-center align-items-center text-white text-center">
    <h1 class="display-4 fw-bold">Rent a vehicle with HastaTravel</h1>
    <p class="lead">Convenient vehicle rentals in UTM, Skudai</p>

    <div class="input-group mt-4" style="max-width:400px;">
      <span class="input-group-text bg-white text-dark border-0">
        <i class="bi bi-search"></i>
      </span>
      <input type="text" class="form-control bg-white text-dark border-0" placeholder="Search for vehicle ...">
      <button class="btn btn-light text-dark" type="button">Search</button>
    </div>

    <div class="mt-3 text-white text-center">
      <h4 class="display-5 fw-bold text-white" style="text-shadow: 2px 2px 5px rgba(0,0,0,0.7);">
        Vehicle Rental
      </h4>
    </div>

    <div class="d-flex justify-content-center" style="position:relative; left:100px;">
      <p class="mt-3 fs-5" style="text-shadow: 2px 2px 5px rgba(0,0,0,0.7);">
        Affordable Vehicles.<br>Unforgettable Trips.
      </p>
    </div>

    <!-- rent now button -->
    <div class="text-center" style="margin-top:60px;">
      <a href="{{ url('/login') }}" class="btn btn-primary btn-lg">Rent Now</a>
    </div>


</section>

{{-- CARS SECTION --}}
<section id="cars-section" class="py-5">
  <div class="container">
    <p class="text-center text-muted mb-0">The <div class="text-center" style="margin-top:30px;">
    <a href="{{ url('/login') }}" class="btn btn-primary btn-lg">Rent Now</a>
</div>
ehicles</p>
    <h2 class="text-center fw-bold mb-5">Our Available Cars</h2>

    <div class="row g-4 justify-content-center">

      <div class="col-md-4 col-sm-6">
        <div class="card shadow-sm rounded-3 p-3">
          <img src="{{ asset('img/car1.png') }}" class="card-img-top" alt="Bezza">
          <div class="card-body text-center">
            <h5 class="card-title fw-bold">Perodua Bezza</h5>
            <p class="card-text text-muted">5-seaters sedan</p>
            <a href="{{ url('/login') }}" class="btn btn-primary">Book Now</a>
          </div>
        </div>
      </div>

      <div class="col-md-4 col-sm-6">
        <div class="card shadow-sm rounded-3 p-3">
          <img src="{{ asset('img/car2.png') }}" class="card-img-top" alt="Aruz">
          <div class="card-body text-center">
            <h5 class="card-title fw-bold">Perodua Aruz</h5>
            <p class="card-text text-muted">7-seaters SUV</p>
            <a href="{{ url('/login') }}" class="btn btn-primary">Book Now</a>
          </div>
        </div>
      </div>

      <div class="col-md-4 col-sm-6">
        <div class="card shadow-sm rounded-3 p-3">
          <img src="{{ asset('img/car3.png') }}" class="card-img-top" alt="Saga">
          <div class="card-body text-center">
            <h5 class="card-title fw-bold">Proton Saga</h5>
            <p class="card-text text-muted">5-seaters sedan</p>
            <a href="{{ url('/login') }}" class="btn btn-primary">Book Now</a>
          </div>
        </div>
      </div>

    </div>

    <div class="text-center mt-5">
      <a href="{{ url('/browse-car') }}" class="btn btn-lg btn-primary">View More</a>
    </div>
  </div>
</section>

@endsection
