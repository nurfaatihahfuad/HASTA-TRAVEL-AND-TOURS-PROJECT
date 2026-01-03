@extends('layouts.app')

@section('content')
<div class="container py-5">
    @if(!empty($query))
        <h2 class="text-center fw-bold mb-5">Search Results for "{{ $query }}"</h2>
    @else
        <h2 class="text-center fw-bold mb-5">Available Cars</h2>
    @endif

    <div class="row g-4">
        @forelse($vehicles as $vehicle)
            <div class="col-md-4 col-sm-6">
                <div class="card shadow-sm rounded-3 p-3">
                    @if($vehicle->image_url)
                        <img src="{{ asset('img/' . $vehicle->image_url) }}" 
                             class="card-img-top" 
                             alt="{{ $vehicle->brand }} {{ $vehicle->model }}">
                    @else
                        <img src="{{ asset('img/default-car.png') }}" 
                             class="card-img-top" 
                             alt="Default Car">
                    @endif

                    <div class="card-body text-center">
                                <h5 class="card-title fw-bold">
                                    {{ $vehicle->brand }} {{ $vehicle->model }} ({{ $vehicle->year }})
                                </h5>
                                <p class="card-text text-muted">{{ $vehicle->description }}</p>
                                <p class="card-text">
                                    <strong>RM{{ number_format($vehicle->price_per_day, 2) }}/day</strong>
                                </p>
                                <a href="{{ route('booking.form', $vehicle->vehicleID ) }}" class="btn btn-primary mx-auto">Book Now</a>
                            </div>
                        </div>
                    </div>
            @empty
                <p class="text-muted text-center">
                    @if(!empty($query))
                        No vehicles found for "{{ $query }}"
                    @else
                        No vehicles available at the moment.
                    @endif
                </p>
            @endforelse
        </div>
    </div>
@endsection

