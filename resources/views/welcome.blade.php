@extends('layouts.app2')

@section('content')

{{-- HERO SECTION --}}
<section class="hero position-relative overflow-hidden" style="min-height: 80vh; background: linear-gradient(rgba(0, 0, 0, 0), rgba(0, 0, 0, 0)), url('{{ asset('img/displayPage.jpg') }}') center/cover no-repeat;">
    <div class="container position-relative z-1 d-flex flex-column justify-content-center align-items-center text-white text-center" style="min-height: 80vh;">
        <h1 class="display-4 fw-bold mb-3">Rent a vehicle with HastaTravel</h1>
        <p class="lead fs-4 mb-4">Convenient vehicle rentals in UTM, Skudai</p>
        
        <div class="mt-4">
            <h4 class="display-5 fw-bold mb-4" style="text-shadow: 2px 2px 5px rgba(0,0,0,0.7);">
                Vehicle Rental
            </h4>
            <p class="fs-5 mb-5" style="text-shadow: 2px 2px 5px rgba(0,0,0,0.7);">
                Affordable Vehicles.<br>Unforgettable Trips.
            </p>
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
        </div>
    </div>
</section>

{{-- CARS SECTION --}}
<section id="cars-section" class="py-5 bg-light" style="background-color: #f8f9fa;">
    <div class="container">
        <h2 class="text-center fw-bold mb-5 display-5">Our Vehicles</h2>
        
        @if($vehicles->isEmpty())
            <div class="text-center py-5">
                <i class="fas fa-car text-muted fs-1 mb-3"></i>
                <p class="text-muted fs-5">No vehicles available at the moment.</p>
                <a href="{{ route('browse.vehicle') }}" class="btn btn-primary mt-3">Check All Vehicles</a>
            </div>
        @else
            <div class="row g-4 justify-content-center">
                @foreach($vehicles as $vehicle)
                    <div class="col-md-4 col-sm-6">
                        <div class="card shadow-sm rounded-3 p-3">
                                @if($vehicle->image_url)
                                    <img src="{{ asset('img/' . $vehicle->image_url) }}" 
                                         class="card-img-top" 
                                         alt="{{ $vehicle->vehicleName }}">
                                @else
                                    <img src="{{ asset('img/default-car.png') }}" 
                                         class="card-img-top" 
                                         alt="Default Car">
                                @endif
                            
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title fw-bold text-truncate">{{ $vehicle->vehicleName }}</h5>
                                <p class="card-text text-muted flex-grow-1" style="font-size: 0.9rem; min-height: 60px;">
                                    {{ Str::limit($vehicle->description, 80) }}
                                </p>
                                <div class="mt-auto">
                                    <p class="card-text fw-bold fs-5 theme-price">RM{{ number_format($vehicle->price_per_day, 2) }}/day</p>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

        @endif
    </div>
</section>

<style>
    /* HERO SECTION STYLES */
    .hero {
        position: relative;
    }
    
    .hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(rgba(0, 0, 0, 0.3), rgba(0, 0, 0, 0.3));
        z-index: 0;
    }
    
    /* CARD HOVER EFFECTS */
    .card {
        border-radius: 15px;
        overflow: hidden;
        height: 100%;
    }
    
    .card:hover {
        transform: translateY(-10px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15) !important;
    }
    
    .card:hover .card-img-top {
        transform: scale(1.05);
    }
    
    .vehicle-image-wrapper {
        background-color: #f8f9fa;
    }
    
    .object-fit-cover {
        object-fit: cover;
    }

    /* Theme color for prices */
    .theme-price {
        color: #dc3545 !important;
        font-weight: 700 !important;
        font-size: 1.25rem !important;
    }

    /* Theme buttons */
    .theme-btn-outline {
        border-color: #dc3545 !important;
        color: #dc3545 !important;
    }

    .theme-btn-outline:hover {
        background-color: #dc3545 !important;
        color: white !important;
    }

    /* Theme background */
    .theme-bg {
        background-color: #fff8f5 !important;
    }
    
    /* RESPONSIVE ADJUSTMENTS */
    @media (max-width: 768px) {
        .hero h1 {
            font-size: 2.5rem;
        }
        
        .hero p.lead {
            font-size: 1.25rem;
        }
        
        .card-body {
            padding: 1rem;
        }
    }
    
    @media (max-width: 576px) {
        .hero {
            min-height: 60vh;
        }
        
        .hero h1 {
            font-size: 2rem;
        }
        
        .hero p.lead {
            font-size: 1.1rem;
        }
        
        .col-md-6 {
            margin-bottom: 1.5rem;
        }
    }
    
    /* ENSURE ALL CARDS HAVE SAME HEIGHT */
    .row {
        display: flex;
        flex-wrap: wrap;
    }
    
    .row > [class*='col-'] {
        display: flex;
        flex-direction: column;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
        
        // Initialize image lazy loading
        const images = document.querySelectorAll('img[data-src]');
        const imageOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px 50px 0px'
        };
        
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src;
                    img.classList.add('loaded');
                    observer.unobserve(img);
                }
            });
        }, imageOptions);
        
        images.forEach(img => imageObserver.observe(img));
    });
</script>

@endsection