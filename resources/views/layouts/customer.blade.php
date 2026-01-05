<!-- resources/views/layouts/customer.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Customer Dashboard') - HASTA</title>
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Custom CSS -->
    @stack('styles')
    
    <style>
        :root {
            --sidebar-width: 250px;
            --primary-color: #4a6cf7;
            --secondary-color: #3a56d5;
            --customer-primary: #4a6cf7;
            --customer-secondary: #3a56d5;
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
            padding: 20px;
            border-bottom: 1px solid #e9ecef;
        }
        
        .sidebar-header h5 {
            color: var(--customer-primary);
            font-weight: 600;
            margin: 0;
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
        
        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }
            
            .main-content {
                margin-left: 0;
            }
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
        
        /* Footer - Kept from app_noHeader */
        .footer {
            background-color: #343a40;
            color: white;
            padding: 20px 0;
            margin-top: auto;
            margin-left: var(--sidebar-width);
        }
        
        .footer-content {
            padding: 0 20px;
        }
        
        @media (max-width: 768px) {
            .footer {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <h5><i class="fas fa-car me-2"></i>HASTA</h5>
        </div>
        
        <!-- Profile Info in Sidebar -->
        <div class="profile-sidebar">
            <div class="profile-avatar">
                {{ substr(auth()->user()->name, 0, 1) }}
            </div>
            <div class="profile-name">{{ auth()->user()->name }}</div>
            <div class="profile-email">{{ auth()->user()->email }}</div>
            
            @php
                $customer = auth()->user()->customer;
                $status = $customer ? $customer->customerStatus : 'pending';
            @endphp
            
            <div class="profile-status status-{{ $status }}">
                {{ ucfirst($status) }}
            </div>
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
            
            <a class="sidebar-link @if($currentRoute == 'customer.profile') active @endif" 
               href="#">
                <i class="fas fa-user"></i> Profile
            </a>
            
            <a class="sidebar-link @if($currentRoute == 'customer.settings') active @endif" 
               href="#">
                <i class="fas fa-cog"></i> Settings
            </a>
            
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
    
    <!-- Footer (from app_noHeader) -->
    <footer class="footer">
        <div class="footer-content">
            <div class="row">
                <div class="col-md-6">
                    <h5>HASTA Car Rental</h5>
                    <p>Your trusted partner for car rentals</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p>&copy; {{ date('Y') }} HASTA. All rights reserved.</p>
                    <p>Contact: info@hasta.com | +60 12-345 6789</p>
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