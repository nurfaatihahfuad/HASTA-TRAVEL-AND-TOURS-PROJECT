<!-- resources/views/layouts/customer.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Customer Dashboard') - HASTA</title>
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Custom CSS -->
    @stack('styles')
    
    <style>
        :root {
            --sidebar-width: 250px;
            --primary-color: #dc3545;
            --secondary-color: #c82333;
            --customer-primary: #dc3545;
            --customer-secondary: #c82333;
        }
        
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        /* Sidebar - Similar to Admin */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background: white;
            z-index: 1000;
            box-shadow: 2px 0 10px rgba(0,0,0,0.05);
            overflow-y: auto;
        }
        
        .sidebar-header {
            padding: 15px;
            border-bottom: 1px solid #e9ecef;
        }
        
        .sidebar-logo {
            max-height: 60px;
            width: auto;
            object-fit: contain;
        }
        
        .sidebar-nav {
            padding: 15px;
        }
        
        .sidebar-link {
            display: block;
            padding: 10px 15px;
            color: #495057;
            text-decoration: none;
            border-radius: 5px;
            margin-bottom: 5px;
            transition: all 0.3s;
        }
        
        .sidebar-link:hover, .sidebar-link.active {
            background-color: #f0f4ff;
            color: var(--customer-primary);
        }
        
        .sidebar-link i {
            width: 20px;
            text-align: center;
            margin-right: 10px;
        }
        
        /* Main content */
        .main-content {
            margin-left: var(--sidebar-width);
            padding: 20px;
            flex: 1;
        }
        
        /* Cards - Similar to Admin */
        .section-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            margin-bottom: 20px;
        }
        
        .metric-card {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            border-left: 4px solid var(--customer-primary);
        }
        
        .metric-title {
            font-size: 0.9rem;
            color: #6c757d;
            margin-bottom: 5px;
        }
        
        .metric-value {
            font-size: 2rem;
            font-weight: 600;
            color: #212529;
        }
        
        /* Profile Section in Sidebar */
        .profile-sidebar {
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid #e9ecef;
        }
        
        .profile-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: var(--customer-primary);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin: 0 auto 10px;
        }
        
        .profile-name {
            font-weight: 600;
            margin-bottom: 5px;
        }
        
        .profile-email {
            font-size: 0.85rem;
            color: #6c757d;
        }
        
        .profile-status {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 0.75rem;
            margin-top: 5px;
        }
        
        .status-active {
            background-color: #d1fae5;
            color: #065f46;
        }
        
        .status-pending {
            background-color: #fef3c7;
            color: #92400e;
        }
        
        /* Footer Styles */
        .footer-main {
            background-color: #f8f9fa;
            padding: 3rem 0;
            margin-top: auto;
            margin-left: var(--sidebar-width);
            border-top: 1px solid #dee2e6;
        }
        
        .footer-main a {
            color: #6c757d;
            text-decoration: none;
            transition: color 0.3s;
        }
        
        .footer-main a:hover {
            color: var(--customer-primary);
        }
        
        .footer-main h6 {
            color: #212529;
            font-weight: 600;
        }
        
        .footer-main ul.list-unstyled li {
            margin-bottom: 0.5rem;
        }
        
        .footer-main .small {
            color: #6c757d;
        }
        
        .footer-main .d-flex.gap-2 a {
            color: #495057;
            transition: color 0.3s;
        }
        
        .footer-main .d-flex.gap-2 a:hover {
            color: var(--customer-primary);
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }
            
            .main-content {
                margin-left: 0;
                padding: 15px;
            }
            
            .footer-main {
                margin-left: 0;
            }
            
            .sidebar-logo {
                max-height: 50px;
            }
        }
        
        @media (max-width: 576px) {
            .sidebar-header {
                padding: 10px;
            }
            
            .sidebar-logo {
                max-height: 45px;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <h6><i class="fas fa-car me-2"></i>HASTA</h6>
        </div>
        
        <!-- Profile Info in Sidebar -->
        <div class="profile-sidebar">
            <div class="profile-avatar">
                {{ substr(auth()->user()->name, 0, 1) }}
            </div>
            <div class="profile-name">{{ auth()->user()->name }}</div>
            <div class="profile-email">{{ auth()->user()->email }}</div>
            @php
                $status = auth()->user()->customer->customerStatus ?? 'inactive';

                $statusClass = match($status) {
                    'active' => 'bg-success',
                    'inactive' => 'bg-warning text-dark',
                    'blacklisted' => 'bg-dark',
                    default => 'bg-secondary'
                };
            @endphp

            <span class="badge {{ $statusClass }} small profile-status">
                {{ ucfirst($status) }}
            </span>
        </div>
        
        <div class="sidebar-nav">
            @php
                $currentRoute = request()->route()->getName();
            @endphp
            
            <a class="sidebar-link @if($currentRoute == 'customer.dashboard') active @endif" 
               href="{{ route('customer.dashboard') }}">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
            
            <a class="sidebar-link @if($currentRoute == 'customer.bookings') active @endif" 
               href="#">
                <i class="fas fa-history"></i> Booking History
            </a>

            <a class="sidebar-link" href="{{ route('inspection.index') }}">
                <i class="fas fa-chart-bar"></i> Car Inspection Checklist
            </a>

            <a class="sidebar-link" href="{{ route('damagecase.index') }}">
                <i class="fas fa-chart-bar"></i> Damage Case Checklist 
            </a>
            
            <a class="sidebar-link @if($currentRoute == 'customer.profile') active @endif" 
               href="{{ route('customer.profile') }}">
                <i class="fas fa-user"></i> Profile
            </a>
            
            <!--<a class="sidebar-link @if($currentRoute == 'customer.settings') active @endif" 
               href="#">
                <i class="fas fa-cog"></i> Settings
            </a>-->
            
            <a class="sidebar-link @if($currentRoute == 'browse.vehicle') active @endif" 
               href="{{ route('browse.vehicle') }}" style="background-color: var(--customer-primary); color: white;">
                <i class="fas fa-calendar-plus"></i> Book Now
            </a>
            
            <div class="mt-4 pt-3 border-top">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger w-100">
                        <i class="fas fa-sign-out-alt me-2"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Main Content -->
    <div class="main-content">
        @yield('content')
    </div>
    
    <!-- Footer -->
    <footer class="footer-main">
        <div class="container">
            <div class="row gy-4">
                <div class="col-md-3">
                    <img src="{{ asset('img/hasta.jpeg') }}" style="max-width:150px; height: auto;" alt="HASTA Logo">
                    <p class="fw-semibold mb-1 mt-2">Hasta Travel & Tours Sdn. Bhd</p>
                    <small class="d-block">SSM : 1359376T</small>
                    <small class="d-block">MOTAC : KPK/LN 10181</small>
                </div>

                <div class="col-md-2">
                    <h6 class="fw-bold border-bottom pb-1 mb-3">Company</h6>
                    <ul class="list-unstyled small">
                        <li><a href="#">About Us</a></li>
                        <li><a href="#">Testimonials</a></li>
                    </ul>
                </div>

                <div class="col-md-2">
                    <h6 class="fw-bold border-bottom pb-1 mb-3">Services</h6>
                    <ul class="list-unstyled small">
                        <li><a href="#">Car Rental</a></li>
                        @if(Route::has('services.tours'))
                            <li><a href="#">Tours</a></li>
                        @endif
                    </ul>
                </div>

                <div class="col-md-2">
                    <h6 class="fw-bold border-bottom pb-1 mb-3">Support</h6>
                    <ul class="list-unstyled small">
                        <li><a href="#">FAQ</a></li>
                        <li><a href="#">Contact</a></li>
                    </ul>
                </div>

                <div class="col-md-3">
                    <h6 class="fw-bold">Need any help?</h6>
                    <p class="small"><i class="bi bi-whatsapp"></i> +60 11-1090 0700</p>
                    <p class="small"><i class="bi bi-envelope"></i> hastatraveltours@gmail.com</p>
                    <div class="d-flex gap-2 fs-5 mt-2">
                        <a href="https://www.instagram.com/hastatraveltours/?hl=en" target="_blank"><i class="bi bi-instagram"></i></a>
                        <a href="https://www.facebook.com/hastatraveltour/" target="_blank"><i class="bi bi-facebook"></i></a>
                        <a href="#"><i class="bi bi-twitter"></i></a>
                        <a href="#"><i class="bi bi-linkedin"></i></a>
                    </div>
                </div>
            </div>
            
            <!-- Copyright -->
            <div class="row mt-4 pt-3 border-top">
                <div class="col-12 text-center">
                    <p class="small text-muted mb-0">&copy; {{ date('Y') }} Hasta Travel & Tours Sdn. Bhd. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Chart.js (if needed) -->
    @stack('scripts')
</body>
</html>