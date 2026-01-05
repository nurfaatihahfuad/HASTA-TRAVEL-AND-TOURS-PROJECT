<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Vehicle Management') - HASTA</title>
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

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

        .section-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            margin-bottom: 20px;
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

            <a class="sidebar-link @if($currentRoute == 'vehicles.index') active @endif" 
               href="{{ route('vehicles.index') }}">
                <i class="fas fa-car"></i> Vehicle Management
            </a>

            <a class="sidebar-link @if($currentRoute == 'vehicles.create') active @endif" 
               href="{{ route('vehicles.create') }}">
                <i class="fas fa-plus-circle"></i> Add Vehicle
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        @yield('content')
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
