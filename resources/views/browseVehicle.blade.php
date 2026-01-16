@extends('layouts.app2')

@section('content')

{{-- HERO SECTION --}}
<section class="hero d-flex flex-column justify-content-center align-items-center text-white text-center py-5" style="min-height: 70vh; background: linear-gradient(to right, #b30000, #660000); position: relative;">
    <div class="container">
        <h1 class="display-3 fw-bold mb-3" style="text-shadow: 2px 2px 5px rgba(0,0,0,0.6);">
            Your Journey Starts Here
        </h1>
        <p class="lead mb-4" style="text-shadow: 1px 1px 3px rgba(0,0,0,0.5);">
            Affordable vehicle rentals in UTM, Skudai — perfect for campus, city, or weekend getaways.
        </p>

        <form action="{{ route('vehicles.search') }}" method="GET" class="bg-white p-4 rounded shadow mx-auto" style="max-width: 600px;">
            <div class="mb-3 text-start">
                <label for="pickup_dateTime" class="form-label fw-bold text-dark">Pick-up Date & Time</label>
                <input type="datetime-local" name="pickup_dateTime"
                    class="form-control bg-white text-dark border-dark"
                    value="{{ request('pickup_dateTime') }}" required>

            </div>

            <div class="mb-3 text-start">
                <label for="return_dateTime" class="form-label fw-bold text-dark">Return Date & Time</label>
                <input type="datetime-local" name="return_dateTime"
                    class="form-control bg-white text-dark border-dark"
                    value="{{ request('return_dateTime') }}" required>

            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-danger px-4">
                    <i class="bi bi-search"></i> Search Vehicles
                </button>
            </div>
        </form>

        <div class="mt-5 text-white">
            <h4 class="display-6 fw-bold" style="text-shadow: 2px 2px 5px rgba(0,0,0,0.7);">Vehicle Rental</h4>
            <p class="fs-5" style="text-shadow: 2px 2px 5px rgba(0,0,0,0.7);">
                Affordable Vehicles.<br>Unforgettable Trips.
            </p>
        </div>
    </div>
</section>


{{-- RESULTS SECTION --}}
<div class="container py-5">
    @if(request()->filled('pickup_dateTime') && request()->filled('return_dateTime'))
        <h2 class="text-center fw-bold mb-5">Available Cars</h2>

        @if($vehicles->isEmpty())
            <p class="text-muted text-center">No vehicles available for the selected dates.</p>
        @else
            <div class="row g-4">
                @foreach($vehicles as $vehicle)
                    <div class="col-md-4 col-sm-6">
                        <div class="card shadow-sm rounded-3 p-3">
                            @if($vehicle->image_url)
                                <img src="{{ asset('img/' . $vehicle->image_url) }}" class="card-img-top" alt="{{ $vehicle->brand }} {{ $vehicle->model }}">
                            @else
                                <img src="{{ asset('img/default-car.png') }}" class="card-img-top" alt="Default Car">
                            @endif

                            <div class="card-body text-center">
                                <h5 class="card-title fw-bold">{{ $vehicle->vehicleName }} ({{ $vehicle->year }})</h5>
                                <p class="card-text text-muted">{{ $vehicle->description }}</p>

                                <p class="card-text"><strong>RM{{ number_format($vehicle->price_per_day, 2) }}/day</strong></p>
                                <a href="{{ route('booking.form', [
                                        'vehicleID' => $vehicle->vehicleID,
                                        'pickup_dateTime' => request('pickup_dateTime'),
                                        'return_dateTime' => request('return_dateTime')
                                ]) }}" class="btn btn-primary mx-auto">
                                Book Now
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    @endif
</div>


@endsection
