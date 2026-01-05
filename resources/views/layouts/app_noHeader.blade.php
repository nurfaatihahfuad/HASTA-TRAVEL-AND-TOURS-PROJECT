<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Hasta Booking System') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <!-- DASHBOARD -->
     <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">

    <!-- Custom CSS -->
    <style>
        
        body { background-color: #f7f6f5; color: #212529; }
        .navbar, footer { background-color: #fff8f5; }
        .navbar .nav-link:hover, .navbar .dropdown-item:hover { color: #dc3545 !important; }
        footer a:hover { color: #dc3545; text-decoration: underline; }
        footer small { color: #6c757d; }

        .carousel-item { height: 70vh; background-size: cover; background-position: center; position: relative; }
        .carousel-caption { bottom: 20%; text-shadow: 0 0 10px rgba(0,0,0,0.7); }

        .card { background-color: #fff8f5 !important; border: 1px solid #dee2e6; transition: transform 0.3s ease, box-shadow 0.3s ease; height: 100%; max-width: 400px; margin: 0 auto;}
        .card-body { display: flex; flex-direction: column; justify-content: space-between; min-height: 250px; /* boleh ubah ikut tinggi ideal */ font-size: 0.9rem; width: fit-content; /* ikut saiz teks, bukan penuh */ align-self: center; /* tengah dalam flex column */}
        .card:hover { transform: translateY(-10px) scale(1.03); box-shadow: 0 15px 25px rgba(0,0,0,0.5); }
        .card .card-title, .card .card-text { color: #212529; }
        .card-img-top { width: 100%; height: auto; max-height: 160px; object-fit: scale-down; border-radius: 0.5rem;}

        .btn-primary { 
            background-color: #dc3545 !important; 
            border-color: #dc3545 !important; 
            transition: all 0.3s ease; 
            padding: 0.4rem 0.8rem; /* atas-bawah = 0.4rem, kiri-kanan = 0.6rem */ 
            font-size: 1.2rem; width: fit-content;
        }

        .card .btn-primary {
            padding: 0.4rem 0.6rem;
            font-size: 0.85rem;
        }

        .btn-primary:hover { background-color: #bb2d3b !important; border-color: #dc3545 !important; }

        footer a {
            color: #000000 !important; /* Black color */
            text-decoration: none !important; /* Remove underline */
        }
        footer a:hover { color: #dc3545 !important; }
        .form-control { background-color: #1e1e1e; color: #e0e0e0; border: 1px solid #6c757d; }
        .form-control:focus { outline: none !important; box-shadow: none !important; border-color: #ced4da !important; /* optional: fallback border */}

        .search-input:focus { outline: none; box-shadow: none; }

        .hero { background-image: url('/img/displayPage.jpg'); background-size: cover; background-position: center; height: 100vh; }
        .card { height: 100%; }
        .card-body { min-height: 180px; }

        
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
        {{-- Page Content --}}
        <main>
            @yield('content')
        </main>

        {{-- Footer shared --}}
        @include('components.footer')

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</body>
</html>
