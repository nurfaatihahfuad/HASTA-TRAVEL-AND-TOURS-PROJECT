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
<<<<<<< HEAD

    <div class="d-flex justify-content-center" style="position:relative; left:100px;">
      <p class="mt-3 fs-5" style="text-shadow: 2px 2px 5px rgba(0,0,0,0.7);">
        Affordable Vehicles.<br>Unforgettable Trips.
      </p>
=======
    <div class="carousel-item" style="background-image: url('{{ asset('img/displayPage.jpg') }}');">
=======
    <div class="carousel-item" style="background-image: url('{{ asset('img/hero2.jpg') }}');">
>>>>>>> 7f0dc0d5ad1d40f9f5795d988b558bb968c22956
=======
<<<<<<<<< Temporary merge branch 1
    <div class="carousel-item" style="background-image: url('{{ asset('img/displayPage.jpg') }}');">
>>>>>>> 912d8c1a0bb168675a5559bef1e968d7ff64a247
      <div class="carousel-caption">
        <h1 class="display-4 fw-bold">Reliable & Safe</h1>
        <p class="lead">Experience worry-free rides with Hasta Travel & Tours.</p>
        <a href="{{ url('browse.cars') }}" class="btn btn-primary btn-lg">Browse Cars</a>
      </div>
<<<<<<< HEAD
<<<<<<< HEAD
    </div>
    <div class="carousel-item" style="background-image: url('{{ asset('img/displayPage.jpg') }}');">
      <div class="carousel-caption">
        <h1 class="display-4 fw-bold">Affordable Rentals</h1>
        <p class="lead">Competitive prices for all your journeys.</p>
        <a href="{{ route('browse.cars') }}" class="btn btn-primary btn-lg">View Cars</a>
      </div>
    </div>
    <div class="carousel-item" style="background-image: url('{{ asset('img/hero3.jpg') }}');">
      <div class="carousel-caption">
        <h1 class="display-4 fw-bold">Affordable Rentals</h1>
        <p class="lead">Competitive prices for all your journeys.</p>
        <a href="{{ route('browse.cars') }}" class="btn btn-primary btn-lg">View Cars</a>
      </div>
=========

    <div class="mt-3 text-white text-center">
        <h4 class="display-5 fw-bold text-white" style="text-shadow: 2px 2px 5px rgba(0,0,0,0.7);">
            Vehicle Rental
        </h4>
    </div>

    <div class="d-flex justify-content-center" style="position:relative; left:100px;">
      <p class="mt-3 fs-5" style="text-shadow: 2px 2px 5px rgba(0,0,0,0.7);">
        Affordable Vehicles.<br>Unforgettable Trips.
      </p>
>>>>>>>>> Temporary merge branch 2
    </div>

    <!-- Rent Now button -->
    <div class="text-center mt-4">
        <a href="{{ url('/login') }}" class="btn btn-primary btn-lg">Rent Now</a>
    </div>
</section>

{{-- CARS SECTION --}}
<section id="cars-section" class="py-5">
  <div class="container">
    <p class="text-center text-muted mb-0">The Cars</p>
    <h2 class="text-center fw-bold mb-5">Our Available Cars</h2>

    <div class="row g-4 justify-content-center">
      @foreach($vehicles as $vehicle)
        <div class="col-md-4 col-sm-6">
          <div class="card shadow-sm rounded-3 p-3">
            @if($vehicle->image_url)
              <img src="{{ asset('img/' . $vehicle->image_url) }}" class="card-img-top" alt="{{ $vehicle->brand }} {{ $vehicle->model }}">
            @else
              <img src="{{ asset('img/default-car.png') }}" class="card-img-top" alt="Default Car">
            @endif
            <div class="card-body text-center">
              <h5 class="card-title fw-bold">{{ $vehicle->brand }} {{ $vehicle->model }}</h5>
              <p class="card-text text-muted">{{ $vehicle->description }}</p>
              <a href="{{ url('/login') }}" class="btn btn-primary">Book Now</a>
            </div>
          </div>
        </div>
      @endforeach
    </div>

    <!-- View More button -->
    <div class="text-center mt-5">
      <a href="{{ url('/browseVehicle') }}" class="btn btn-lg btn-primary">View More</a>
    </div>
  </div>
</section>


@endsection
