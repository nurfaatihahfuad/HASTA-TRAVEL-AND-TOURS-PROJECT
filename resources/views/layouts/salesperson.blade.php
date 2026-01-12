<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Salesperson Dashboard') - HASTA</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @stack('styles')
    
    <style>
        :root {
            --sidebar-width: 260px;
            --sidebar-collapsed-width: 70px;
            --primary-color: #dc3545;
            --secondary-color: #c82333;
            --transition-speed: 0.3s;
        }
        
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow-x: hidden;
        }
        
        /* Sidebar Base */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background: white;
            z-index: 1000;
            box-shadow: 2px 0 10px rgba(0,0,0,0.05);
            transition: width var(--transition-speed) ease;
            overflow-x: hidden;
        }
        
        /* Sidebar Collapsed State */
        .sidebar.collapsed {
            width: var(--sidebar-collapsed-width);
        }

        .sidebar-header {
            padding: 20px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            align-items: center;
            justify-content: space-between;
            white-space: nowrap;
        }
        
        .sidebar-header h5 {
            color: var(--primary-color);
            font-weight: 700;
            margin: 0;
            display: inline-block;
        }

        /* Hide text when collapsed */
        .sidebar.collapsed .sidebar-header h5,
        .sidebar.collapsed .menu-text,
        .sidebar.collapsed hr {
            display: none;
        }

        .sidebar-nav {
            padding: 10px;
        }
        
        .sidebar-link {
            display: flex;
            align-items: center;
            padding: 12px 15px;
            color: #495057;
            text-decoration: none;
            border-radius: 8px;
            margin-bottom: 5px;
            transition: all var(--transition-speed);
            white-space: nowrap;
        }
        
        .sidebar-link:hover, .sidebar-link.active {
            background-color: #fff1f2;
            color: var(--primary-color);
        }
        
        .sidebar-link i {
            font-size: 1.2rem;
            min-width: 35px;
            text-align: center;
        }

        /* Center icons when collapsed */
        .sidebar.collapsed .sidebar-link {
            justify-content: center;
            padding: 12px 0;
        }
        
        .sidebar.collapsed .sidebar-link i {
            margin: 0;
        }

        /* Main content adjustment */
        .main-content {
            margin-left: var(--sidebar-width);
            padding: 30px;
            min-height: 100vh;
            transition: margin-left var(--transition-speed) ease;
        }

        .main-content.expanded {
            margin-left: var(--sidebar-collapsed-width);
        }

        #toggleBtn {
            cursor: pointer;
            color: #6c757d;
            border: none;
            background: none;
            font-size: 1.2rem;
        }
        
        /* Dashboard specific styles */
        .metric-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            border: 1px solid #e9ecef;
            transition: transform 0.3s;
        }
        
        .metric-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 15px rgba(0,0,0,0.1);
        }
        
        .metric-title {
            font-size: 14px;
            color: #6c757d;
            margin-bottom: 8px;
            font-weight: 500;
        }
        
        .metric-value {
            font-size: 32px;
            font-weight: 700;
            color: #212529;
        }
        
        .section-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            border: 1px solid #e9ecef;
            height: 100%;
        }
        
        /* Custom table styles */
        .table th {
            font-weight: 600;
            color: #495057;
            border-bottom: 2px solid #dee2e6;
            background-color: #f8f9fa;
        }
        
        .table td {
            vertical-align: middle;
        }
        
        .btn-group-sm .btn {
            padding: 0.25rem 0.5rem;
        }

        /* Responsive Mobile */
        @media (max-width: 768px) {
            .sidebar {
                left: -100%;
            }
            .sidebar.show-mobile {
                left: 0;
                width: var(--sidebar-width) !important;
            }
            .main-content {
                margin-left: 0 !important;
                padding: 15px;
            }
            
            .metric-value {
                font-size: 24px;
            }
        }
    </style
</head>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<body>
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h5><img src="{{ asset('img/hasta.jpeg') }}" alt="Hasta Logo" style="max-width:100px;"></h5>
            <button id="toggleBtn"><i class="fas fa-bars"></i></button>
        </div>
        
        <div class="sidebar-nav">
            @php $currentRoute = request()->route()->getName(); @endphp
            
            <a class="sidebar-link @if($currentRoute == 'salesperson.dashboard') active @endif" 
               href="{{ route('staff_salesperson.dashboard') }}">
                <i class="fas fa-tachometer-alt"></i>
                <span class="menu-text">Dashboard</span>
            </a>
            
            <a class="sidebar-link @if($currentRoute == 'record.payment') active @endif" href="{{ route('record.payment') }}">
                <i class="fas fa-clipboard-check"></i>
                <span class="menu-text">Payment Record</span>
            </a>

            <a class="sidebar-link @if($currentRoute == 'commission.index') active @endif" href="{{ route('commission.index') }}">
                <i class="fas fa-coins"></i>
                <span class="menu-text">Commission</span>
            </a>

            <a class="sidebar-link @if($currentRoute == 'staff.inspections.index') active @endif" href="{{ route('staff.inspections.index') }}">
                <i class="fas fa-file-invoice"></i>
                <span class="menu-text">Inspections</span>
            </a>

            <a class="sidebar-link @if($currentRoute == 'damagecase.index') active @endif" href="{{ route('damagecase.index') }}">
                <i class="fas fa-car-burst"></i>
                <span class="menu-text">Damage Cases</span>
            </a>
            
            <hr>
            
            <a class="sidebar-link @if($currentRoute == 'salesperson.profile.edit') active @endif" href="{{ route('salesperson.profile.edit') }}">
                <i class="fas fa-user"></i>
                <span class="menu-text">Profile</span>
            </a>
            
            <form method="POST" action="{{ route('logout') }}" class="mt-3">
                @csrf
                <button type="submit" class="btn btn-outline-danger w-100 d-flex align-items-center justify-content-center">
                    <i class="fas fa-sign-out-alt"></i>
                    <span class="menu-text ms-2">Logout</span>
                </button>
            </form>
        </div>
    </div>
    
    <div class="main-content" id="mainContent">
        @yield('content')
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('mainContent');
        const toggleBtn = document.getElementById('toggleBtn');

        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');
            mainContent.classList.toggle('expanded');
        });
    </script>
    @stack('scripts')
</body>
</html>