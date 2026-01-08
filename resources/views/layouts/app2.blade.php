<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Hasta Car Rental')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <!-- Your Custom CSS -->
    <style>
        /* NAVBAR STYLES */
        .navbar {
            background-color: #fff8f5 !important;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }
        
        .navbar-brand img {
            height: 40px;
            object-fit: contain;
            transition: transform 0.3s ease;
        }
        
        .navbar-brand img:hover {
            transform: scale(1.05);
        }
        
        .nav-link {
            font-weight: 500;
            color: #333 !important;
            padding: 0.5rem 1rem !important;
            transition: all 0.3s ease;
            border-radius: 4px;
            margin: 0 2px;
        }
        
        .nav-link:hover, 
        .nav-link:focus,
        .nav-link.active {
            color: #dc3545 !important;
            background-color: rgba(220, 53, 69, 0.05);
        }
        
        /* HOVER DROPDOWN STYLES - FOR DESKTOP */
        @media (min-width: 992px) {
            .dropdown-hover:hover .dropdown-menu,
            .dropdown-hover .dropdown-toggle:hover + .dropdown-menu,
            .dropdown-hover .nav-link:focus + .dropdown-menu {
                display: block !important;
                opacity: 1 !important;
                visibility: visible !important;
                transform: translateY(0) !important;
                pointer-events: auto !important;
            }
            
            .dropdown-hover .dropdown-menu {
                display: block !important;
                opacity: 0;
                visibility: hidden;
                transform: translateY(-10px);
                transition: all 0.3s ease;
                pointer-events: none;
                margin-top: 0;
            }
            
            .dropdown-hover:hover > .dropdown-menu {
                opacity: 1;
                visibility: visible;
                transform: translateY(0);
                pointer-events: auto;
            }
            
            /* Keep dropdown open when hovering over menu */
            .dropdown-menu:hover {
                display: block !important;
                opacity: 1 !important;
                visibility: visible !important;
            }
            
            /* Adjust caret for hover dropdown */
            .dropdown-hover .dropdown-toggle::after {
                content: "▾";
                border: none;
                font-size: 1.2em;
                vertical-align: middle;
                margin-left: 0.5rem;
            }
        }
        
        .dropdown-menu {
            border: none;
            border-radius: 0.5rem;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            padding: 0.5rem 0;
            animation: fadeIn 0.2s ease-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .dropdown-item {
            padding: 0.75rem 1.5rem;
            font-weight: 500;
            transition: all 0.2s ease;
            color: #333;
            position: relative;
        }
        
        .dropdown-item:hover,
        .dropdown-item:focus {
            background-color: #dc3545 !important;
            color: white !important;
            padding-left: 2rem;
        }
        
        /* Mobile responsiveness */
        @media (max-width: 991.98px) {
            .navbar-nav {
                padding: 1rem 0;
            }
            
            .nav-item {
                margin: 5px 0;
            }
            
            .dropdown-menu {
                border: none;
                box-shadow: none;
                background-color: transparent;
                padding-left: 1.5rem;
            }
            
            .dropdown-item {
                padding: 0.5rem 1rem;
            }
            
            .dropdown-hover .dropdown-toggle::after {
                content: "▸";
                border: none;
                font-size: 1.2em;
                float: right;
                margin-top: 0.3rem;
            }
        }
        
        /* Footer styling */
        footer {
            background-color: #fff8f5;
            border-top: 1px solid #e9ecef;
            margin-top: auto;
        }
        
        footer h6 {
            color: #333;
        }
        
        footer a {
            color: #666;
            text-decoration: none;
            transition: color 0.2s ease;
        }
        
        footer a:hover {
            color: #dc3545;
        }
        
        footer .bi-whatsapp {
            color: #25D366;
        }
        
        footer .bi-envelope {
            color: #dc3545;
        }
        
        footer .fs-5 a {
            color: #666;
            transition: all 0.3s ease;
        }
        
        footer .fs-5 a:hover {
            transform: translateY(-3px);
        }
        
        footer .bi-instagram:hover {
            color: #E4405F;
        }
        
        footer .bi-facebook:hover {
            color: #1877F2;
        }
        
        footer .bi-twitter:hover {
            color: #1DA1F2;
        }
        
        footer .bi-linkedin:hover {
            color: #0A66C2;
        }
        
        /* Page content styling - ensures footer stays at bottom */
        html, body {
            height: 100%;
        }
        
        body {
            display: flex;
            flex-direction: column;
        }
        
        main.content {
            flex: 1 0 auto;
            min-height: calc(100vh - 400px); /* Adjust based on your navbar/footer height */
        }
        
        footer {
            flex-shrink: 0;
        }
        
        /* Search form styling */
        .navbar .form-control {
            border-radius: 0.375rem;
            border: 1px solid #dee2e6;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }
        
        .navbar .form-control:focus {
            border-color: #dc3545;
            box-shadow: 0 0 0 0.25rem rgba(220, 53, 69, 0.25);
        }
        
        .navbar .btn-primary {
            background-color: #dc3545;
            border-color: #dc3545;
            border-radius: 0.375rem;
            transition: all 0.3s ease;
        }
        
        .navbar .btn-primary:hover {
            background-color: #c82333;
            border-color: #bd2130;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(220, 53, 69, 0.3);
        }
        .card {
            background-color: #fff8f5 !important;
        }
    </style>
    
    @yield('styles')
</head>
<body>
    <!-- HEADER/NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm sticky-top">
        <div class="container-fluid">
            <!-- Logo -->
            <a class="navbar-brand d-flex align-items-center" href="/">
                <img src="{{ asset('img/hasta.jpeg') }}" alt="Hasta Logo" style="height:40px;">
            </a>

            <!-- Mobile toggle button -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain"
                aria-controls="navbarMain" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Navbar content -->
            <div class="collapse navbar-collapse" id="navbarMain">
                <!-- Navigation links -->
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link fw-semibold {{ request()->is('/') ? 'active' : '' }}" href="/">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-semibold {{ request()->is('browse-car*') ? 'active' : '' }}" href="/browse-car">Book Car</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-semibold {{ request()->is('about*') ? 'active' : '' }}" href="#">About Us</a>
                    </li>
                    
                    <!-- User Dropdown - HOVER ENABLED -->
                    <li class="nav-item dropdown dropdown-hover position-static">
                        <a class="nav-link dropdown-toggle fw-semibold" href="#" id="navbarDropdown" 
                           role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user me-1"></i> User
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            @if(auth()->check())
                                <li><span class="dropdown-item-text">Welcome, {{ auth()->user()->name }}</span></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item" href="#">
                                        <i class="fas fa-history me-2"></i>Booking History
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#">
                                        <i class="fas fa-cog me-2"></i>Account Settings
                                    </a>
                                </li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <a class="dropdown-item" href="#" onclick="event.preventDefault(); this.closest('form').submit();">
                                            <i class="fas fa-sign-out-alt me-2"></i>Logout
                                        </a>
                                    </form>
                                </li>
                            @else
                                <li>
                                    <a class="dropdown-item" href="{{ route('login') }}">
                                        <i class="fas fa-sign-in-alt me-2"></i>Log In
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('customer.register') }}">
                                        <i class="fas fa-user-plus me-2"></i>Sign Up
                                    </a>
                                </li>
                            @endif
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-question-circle me-2"></i>Help Center
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>

                <!-- Search form -->
                <!--<form class="d-flex" role="search">
                    <div class="input-group">
                        <input class="form-control" type="search" placeholder="Search cars..." 
                               aria-label="Search">
                        <button class="btn btn-primary" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>-->
            </div>
        </div>
    </nav>

    <!-- MAIN CONTENT -->
    <main class="content">
        @yield('content')
    </main>

    <!-- FOOTER -->
    <footer class="py-5">
        <div class="container">
            <div class="row gy-4">
                <div class="col-md-3">
                    <img src="{{ asset('img/hasta.jpeg') }}" alt="Hasta Logo" style="max-width:150px;">
                    <p class="fw-semibold mb-1 mt-2">Hasta Travel & Tours Sdn. Bhd</p>
                    <small class="d-block">SSM : 1359376T</small>
                    <small class="d-block">MOTAC : KPK/LN 10181</small>
                </div>

                <div class="col-md-2">
                    <h6 class="fw-bold border-bottom pb-1 mb-3">Company</h6>
                    <ul class="list-unstyled small">
                        <li class="mb-2"><a href="#">About Us</a></li>
                        <li><a href="#">Testimonials</a></li>
                    </ul>
                </div>

                <div class="col-md-2">
                    <h6 class="fw-bold border-bottom pb-1 mb-3">Services</h6>
                    <ul class="list-unstyled small">
                        <li><a href="#">Car Rental</a></li>
                    </ul>
                </div>

                <div class="col-md-2">
                    <h6 class="fw-bold border-bottom pb-1 mb-3">Support</h6>
                    <ul class="list-unstyled small">
                        <li class="mb-2"><a href="#">FAQ</a></li>
                        <li><a href="#">Contact</a></li>
                    </ul>
                </div>

                <div class="col-md-3">
                    <h6 class="fw-bold border-bottom pb-1 mb-3">Need any help?</h6>
                    <p class="small mb-2"><i class="bi bi-whatsapp"></i> +60 11-1090 0700</p>
                    <p class="small mb-3"><i class="bi bi-envelope"></i> hastatraveltours@gmail.com</p>
                    <div class="d-flex gap-3 fs-5">
                        <a href="https://www.instagram.com/hastatraveltours/?hl=en" target="_blank"><i class="bi bi-instagram"></i></a>
                        <a href="https://www.facebook.com/hastatraveltour/" target="_blank"><i class="bi bi-facebook"></i></a>
                        <a href="#"><i class="bi bi-twitter"></i></a>
                        <a href="#"><i class="bi bi-linkedin"></i></a>
                    </div>
                </div>
            </div>
            
            <div class="row mt-4 pt-4 border-top">
                <div class="col-12 text-center">
                    <small class="text-muted">&copy; {{ date('Y') }} Hasta Travel & Tours Sdn. Bhd. All rights reserved.</small>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Font Awesome for icons -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
    
    <!-- Custom JavaScript -->
    <script>
        // Hover dropdown functionality
        document.addEventListener('DOMContentLoaded', function() {
            const dropdown = document.querySelector('.dropdown-hover');
            if (!dropdown) return;
            
            const dropdownToggle = dropdown.querySelector('.dropdown-toggle');
            const dropdownMenu = dropdown.querySelector('.dropdown-menu');
            
            // Check if we're on desktop (hover capable)
            function isDesktop() {
                return window.innerWidth >= 992;
            }
            
            // Handle hover on desktop
            if (isDesktop()) {
                // Prevent Bootstrap's default click toggle on desktop
                dropdownToggle.addEventListener('click', function(e) {
                    if (isDesktop()) {
                        e.preventDefault();
                    }
                });
                
                // Show on hover
                dropdown.addEventListener('mouseenter', function() {
                    if (isDesktop()) {
                        dropdownMenu.style.display = 'block';
                        setTimeout(() => {
                            dropdownMenu.style.opacity = '1';
                            dropdownMenu.style.visibility = 'visible';
                            dropdownMenu.style.transform = 'translateY(0)';
                        }, 10);
                    }
                });
                
                // Hide when mouse leaves both button and menu
                dropdown.addEventListener('mouseleave', function(e) {
                    if (isDesktop()) {
                        setTimeout(() => {
                            if (!dropdown.matches(':hover') && !dropdownMenu.matches(':hover')) {
                                dropdownMenu.style.opacity = '0';
                                dropdownMenu.style.visibility = 'hidden';
                                dropdownMenu.style.transform = 'translateY(-10px)';
                                setTimeout(() => {
                                    dropdownMenu.style.display = 'none';
                                }, 300);
                            }
                        }, 100);
                    }
                });
                
                // Also handle mouse leave from dropdown menu
                dropdownMenu.addEventListener('mouseleave', function(e) {
                    if (isDesktop() && !dropdown.contains(e.relatedTarget)) {
                        dropdownMenu.style.opacity = '0';
                        dropdownMenu.style.visibility = 'hidden';
                        dropdownMenu.style.transform = 'translateY(-10px)';
                        setTimeout(() => {
                            dropdownMenu.style.display = 'none';
                        }, 300);
                    }
                });
            }
            
            // Add active state to clicked nav items
            document.querySelectorAll('.nav-link').forEach(link => {
                link.addEventListener('click', function() {
                    document.querySelectorAll('.nav-link').forEach(l => l.classList.remove('active'));
                    this.classList.add('active');
                });
            });
        });
    </script>
    
    @yield('scripts')
</body>
</html>