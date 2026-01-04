@extends('layouts.app')

@section('content')

{{-- HERO SECTION --}}
<section class="hero d-flex flex-column justify-content-center align-items-center text-white text-center">
    <h1 class="display-4 fw-bold">Rent a vehicle with HastaTravel</h1>
    <p class="lead">Convenient vehicle rentals in UTM, Skudai</p>

    <div class="mt-3 text-white text-center">
      <h4 class="display-5 fw-bold text-white" style="text-shadow: 2px 2px 5px rgba(0,0,0,0.7);">
        Vehicle Rental
      </h4>
    </div>

    <div class="d-flex justify-content-center">
      <p class="mt-3 fs-5" style="text-shadow: 2px 2px 5px rgba(0,0,0,0.7);">
        Affordable Vehicles.<br>Unforgettable Trips.
      </p>

    <div class="carousel-item" style="background-image: url('{{ asset('img/displayPage.jpg') }}');"> </div>

    <div class="carousel-item" style="background-image: url('{{ asset('img/displayPage.jpg') }}');"> </div>


      <div class="carousel-caption">
        <h1 class="display-4 fw-bold">Reliable & Safe</h1>
        <p class="lead">Experience worry-free rides with Hasta Travel & Tours.</p>
        <a href="{{ route('browse.vehicle') }}" class="btn btn-primary btn-lg">Browse Cars</a>
      </div>

    </div>
    <div class="carousel-item" style="background-image: url('{{ asset('img/displayPage.jpg') }}');">
      <div class="carousel-caption">
        <h1 class="display-4 fw-bold">Affordable Rentals</h1>
        <p class="lead">Competitive prices for all your journeys.</p>
        <a href="{{ route('browse.vehicle') }}" class="btn btn-primary btn-lg">View Cars</a>
      </div>
    </div>
    <div class="carousel-item" style="background-image: url('{{ asset('img/hero3.jpg') }}');">
      <div class="carousel-caption">
        <h1 class="display-4 fw-bold">Affordable Rentals</h1>
        <p class="lead">Competitive prices for all your journeys.</p>
        <a href="{{ route('browse.vehicle') }}" class="btn btn-primary btn-lg">View Cars</a>
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
</section>

{{-- CARS SECTION --}}
<section id="cars-section" class="py-5">
  <div class="container">
    <h2 class="text-center fw-bold mb-5 display-6">Our Vehicles</h2>

    @if($vehicles->isEmpty())
      <p class="text-center text-muted">No vehicles available for the selected dates.</p>
    @else
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
                <p class="card-text text-muted">{{ $vehicle->description }}</p>
                <p class="card-text fw-bold">RM{{ $vehicle->price_per_day }}/hour</p>
              </div>
            </div>
          </div>
        @endforeach
      </div>
    @endif
  </div>
</section>

@endsection
