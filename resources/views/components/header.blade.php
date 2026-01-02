<!-- header.blade.php -->
<!-- Remove ALL HTML, head, body tags - ONLY keep navbar markup -->

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  
  <style>
    /* Body dark mode */
    body {
      background-color: #fffcfcff;
      color: #e0e0e0;
    }

    /* Navbar & Footer */
    .navbar, footer {
      background-color: #ffffffff;
    }
    .navbar .nav-link, .navbar-brand img {
      color: #e0e0e0 !important;
    }
    .navbar .nav-link:hover, .navbar .dropdown-item:hover {
      color: #c0151dff !important;
    }
    footer a {
      color: #e0e0e0;
    }
    footer a:hover {
      color: #c0151dff;
      text-decoration: underline;
    }
    footer small {
      color: #b0b0b0;
    }

    /* Hero carousel */
    .carousel-item {
      height: 70vh;
      background-size: cover;
      background-position: center;
      position: relative;
    }
    .carousel-caption {
      bottom: 20%;
      text-shadow: 0 0 10px rgba(0,0,0,0.7);
    }

    /* Cards */
    .card {
      background-color: #1e1e1e;
      border: none;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .card:hover {
      transform: translateY(-10px) scale(1.03);
      box-shadow: 0 15px 25px rgba(0,0,0,0.5);
    }
    .card .card-title, .card .card-text {
      color: #e0e0e0;
    }
    .card .btn-primary {
      background-color: #c0151dff;
      border-color: #c0151dff;
      transition: all 0.3s ease;
    }
    .card .btn-primary:hover {
      background-color: #c0151dff;
      border-color: #c0151dff;
    }

    /* Form search */
    .form-control {
      background-color: #1e1e1e;
      color: #e0e0e0;
      border: 1px solid #6c757d;
    }
  </style>
</head>
<body>

<!-- NAVBAR --> 
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
            <li><a class="dropdown-item" href="{{ route('register') }}">Sign Up</a></li>
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