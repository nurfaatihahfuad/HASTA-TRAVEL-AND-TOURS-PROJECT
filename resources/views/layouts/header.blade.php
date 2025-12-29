<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Hasta Booking System</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  {{-- Bootstrap CSS --}}
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  
  <style>
    /* Body dark mode */
    body {
      background-color: #121212;
      color: #e0e0e0;
    }

    /* Navbar & Footer */
    .navbar, footer {
      background-color: #1f1f1f;
    }
    .navbar .nav-link, .navbar-brand img {
      color: #e0e0e0 !important;
    }
    .navbar .nav-link:hover, .navbar .dropdown-item:hover {
      color: #0d6efd !important;
    }
    footer a {
      color: #e0e0e0;
    }
    footer a:hover {
      color: #0d6efd;
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
      background-color: #0d6efd;
      border-color: #0d6efd;
      transition: all 0.3s ease;
    }
    .card .btn-primary:hover {
      background-color: #0b5ed7;
      border-color: #0b5ed7;
    }

    /* Form search */
    .form-control {
      background-color: #1e1e1e;
      color: #e0e0e0;
      border: 1px solid #6c757d;
    }
  </style>
</head>