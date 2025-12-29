@include('layouts.header')

{{-- NAVBAR --}}
<nav class="navbar navbar-expand-lg navbar-dark sticky-top">
  <div class="container">
    <a class="navbar-brand" href="/"><img src="{{ asset('img/hasta.jpeg') }}" alt="Hasta Logo" style="max-height:50px;"></a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarColor03">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarColor03">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link active" href="/">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="/browse-car">Book Car</a></li>
        <li class="nav-item"><a class="nav-link" href="#">About Us</a></li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#">User</a>
          <ul class="dropdown-menu dropdown-menu-dark">
            <li><a class="dropdown-item" href="{{ url('/login') }}">Log In</a></li>
            <li><a class="dropdown-item" href="{{ url('/register') }}">Sign Up</a></li>
          </ul>
        </li>
      </ul>
      <form class="d-flex" role="search">
        <input class="form-control me-2" type="search" placeholder="Search">
        <button class="btn btn-primary" type="submit">Search</button>
      </form>
    </div>
  </div>
</nav>

{{-- HERO CAROUSEL --}}
<div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
  <div class="carousel-inner">
    <div class="carousel-item active" style="background-image: url('{{ asset('img/displayPage.jpg') }}');">
      <div class="carousel-caption">
        <h1 class="display-4 fw-bold">Drive with Comfort</h1>
        <p class="lead">Premium vehicles for your travel needs.</p>
        <a href="{{ url('browse.cars') }}" class="btn btn-primary">Book Now</a>
      </div>
    </div>
    <div class="carousel-item" style="background-image: url('{{ asset('img/displayPage.jpg') }}');">
      <div class="carousel-caption">
        <h1 class="display-4 fw-bold">Reliable & Safe</h1>
        <p class="lead">Experience worry-free rides with Hasta Travel & Tours.</p>
        <a href="{{ url('browse.cars') }}" class="btn btn-primary btn-lg">Browse Cars</a>
      </div>
    </div>
    <div class="carousel-item" style="background-image: url('{{ asset('img/displayPage.jpg') }}');">
      <div class="carousel-caption">
        <h1 class="display-4 fw-bold">Affordable Rentals</h1>
        <p class="lead">Competitive prices for all your journeys.</p>
        <a href="{{ route('browse.cars') }}" class="btn btn-primary btn-lg">View Cars</a>
      </div>
    </div>
  </div>
  <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
    <span class="carousel-control-prev-icon"></span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
    <span class="carousel-control-next-icon"></span>
  </button>
</div>

{{-- CARS SECTION --}}
<section id="cars-section" class="py-5">
  <div class="container">
    <p class="text-center text-muted mb-0">The Cars</p>
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

@include('layouts.footer')