@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h2 class="text-center fw-bold mb-5">Available Cars</h2>

    <div class="row g-4">
        @foreach($vehicles as $vehicle)
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
                        <a href="{{ url('/login') }}" class="btn btn-primary">Book Now</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
