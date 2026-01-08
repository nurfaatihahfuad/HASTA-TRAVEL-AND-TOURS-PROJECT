<!--Runner Navbar-->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Runner Dashboard') - HASTA</title>
    
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Chart.js (if needed) -->
    @stack('styles')
    
    <style>
        :root {
            --sidebar-width: 250px;
            --primary-color: #dc3545;
            --secondary-color: #c82333;
        }
        
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        /* Sidebar */
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
            color: var(--primary-color);
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
            background-color: #f8f9fa;
            color: var(--primary-color);
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
            min-height: 100vh;
        }
        
        /* Cards */
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
        
        .metric-delta {
            font-size: 0.8rem;
            color: #28a745;
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
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <h5><i class="fas fa-car me-2"></i>HASTA</h5>
        </div>
        
        <div class="sidebar-nav">
            @php
                $currentRoute = request()->route()->getName();
            @endphp
            
            <a class="sidebar-link @if($currentRoute == 'staff_runner.dashboard') active @endif" 
               href="{{ route('staff_runner.dashboard') }}">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>

            <a class="sidebar-link" href="{{ route('inspection.index') }}">
                <i class="fas fa-chart-bar"></i> Car Inspection Checklist
            </a>

            <a class="sidebar-link" href="{{ route('damagecase.index') }}">
                <i class="fas fa-chart-bar"></i> Damage Case Checklist 
            </a>
            
            <hr>
            
            <a class="sidebar-link" href="#">
                <i class="fas fa-user"></i> Profile
            </a>
            
            <a class="sidebar-link" href="#">
                <i class="fas fa-cog"></i> Settings
            </a>
            
            <form method="POST" action="{{ route('logout') }}" class="mt-3">
                @csrf
                <button type="submit" class="btn btn-outline-danger w-100">
                    <i class="fas fa-sign-out-alt me-2"></i> Logout
                </button>
            </form>
        </div>
    </div>
    
    <!-- Main Content -->
    <div class="main-content">
        @yield('content')
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Chart.js (if needed) -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    @stack('scripts')
</body>
</html>