<!-- <x-guest-layout>
<div class="min-h-screen bg-gray-900 text-gray-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-6xl mx-auto">
        <h1 class="text-3xl md:text-4xl font-bold text-center mb-8">Browse Our Cars</h1>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            @foreach($cars as $car)
            <div class="bg-gray-800 rounded-lg shadow p-4 text-center">
                <img src="{{ asset('img/' . $car['image']) }}" alt="{{ $car['name'] }}" class="mx-auto mb-3 h-40 w-auto">
                <h3 class="font-semibold text-lg">{{ $car['name'] }}</h3>
                <p class="text-gray-400 mb-3">{{ $car['seats'] }} seats</p>
                @guest
                    <a href="{{ route('register') }}" class="btn-primary mt-3 inline-block px-4 py-2 rounded">Book Now</a>
                @else
                    <a href="#" class="btn-primary mt-3 inline-block px-4 py-2 rounded">Book Now</a>
                @endguest
            </div>
            @endforeach

        </div>
    </div>
</div>
</x-guest-layout> --> 
@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h1 class="text-center text-white mb-4">Browse Available Cars</h1>
    <div class="row g-4">
        <div class="col-md-4">
            <div class="card bg-dark text-white">
                <img src="{{ asset('img/car1.png') }}" class="card-img-top" alt="Car 1">
                <div class="card-body text-center">
                    <h5 class="card-title">Perodua Bezza</h5>
                    <p class="card-text">5-seaters sedan</p>
                    <a href="{{ route('login') }}" class="btn btn-primary">Book Now</a>
                </div>
            </div>
        </div>
        <!-- Tambah lagi cards -->
    </div>
</div>
@endsection

