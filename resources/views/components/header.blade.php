<!-- NAVBAR 
<nav class="navbar navbar-expand-lg navbar-dark sticky-top">
  <div class="container">
    <a class="navbar-brand" href="/">
      <img src="{{ asset('img/hasta.jpeg') }}" alt="Hasta Logo" style="max-height:50px;">
    </a>
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
            <li><a class="dropdown-item" href="{{ route('login') }}">Log In</a></li>
            <li><a class="dropdown-item" href="{{ route('customer.register') }}">Sign Up</a></li>
          </ul>
        </li>
      </ul>
      <form class="d-flex" role="search">
        <input class="form-control me-2" type="search" placeholder="Search">
        <button class="btn btn-primary" type="submit">Search</button>
      </form>
    </div>
  </div>
</nav> --> 

<!-- HEADER START -->
<nav class="navbar navbar-light bg-white border-bottom py-2 px-3 shadow-sm">
  <div class="container-fluid d-flex justify-content-between align-items-center">
    <!-- Logo + Brand -->
    <a class="navbar-brand d-flex align-items-center" href="/">
      <img src="{{ asset('img/hasta.jpeg') }}" alt="Hasta Logo" style="height:40px;">
    </a>

    <!-- Navigation Links (centered) -->
    <ul class="navbar-nav mx-auto flex-row">
      <li class="nav-item me-3">
        <a class="nav-link fw-semibold text-dark" href="/">Home</a>
      </li>
      <li class="nav-item me-3">
        <a class="nav-link fw-semibold text-dark" href="#">About Us</a>
      </li>
      <!-- User Dropdown -->
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle fw-semibold text-dark" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
          User
        </a>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
          <li><a class="dropdown-item" href="{{ route('login') }}">Log In</a></li>
          <li><a class="dropdown-item" href="{{ route('customer.register') }}">Sign Up</a></li>
        </ul>
      </li>
    </ul>
  </div>
</nav>

<!-- STYLE -->
<style>
  .navbar {
    background-color: #fff8f5; /* soft background */
  }
  .navbar-brand img {
    height: 40px;
    object-fit: contain;
  }
  .navbar-brand span {
    font-family: 'Figtree', sans-serif;
    letter-spacing: 1px;
  }
  .nav-link {
    font-size: 0.95rem;
    transition: color 0.2s ease;
  }
  .nav-link:hover {
    color: #dc3545 !important; /* red hover */
  }
  .dropdown-menu {
    border-radius: 0.5rem;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
  }
  .dropdown-item:hover {
    background-color: #f8f9fa;
    color: #dc3545 !important;
  }
</style>
<!-- HEADER END -->

<!-- Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>