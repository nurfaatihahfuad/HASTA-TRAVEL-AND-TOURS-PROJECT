<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Customer Dashboard') - HASTA</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @stack('styles')
    
    <style>
        :root {
            --sidebar-width: 260px;
            --sidebar-collapsed-width: 80px;
            --customer-primary: #dc3545;
            --customer-secondary: #c82333;
            --transition-speed: 0.3s;
        }
        
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            overflow-x: hidden;
        }
        
        /* Sidebar Configuration */
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
            display: flex;
            flex-direction: column;
        }
        
        .sidebar.collapsed {
            width: var(--sidebar-collapsed-width);
        }

        .sidebar-header {
            padding: 15px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        /* Hide text/profile when collapsed */
        .sidebar.collapsed .menu-text, 
        .sidebar.collapsed .profile-sidebar, 
        .sidebar.collapsed .sidebar-header h6 {
            display: none;
        }

        .sidebar-nav {
            padding: 15px;
            flex-grow: 1;
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
            background-color: #f0f4ff;
            color: var(--customer-primary);
        }

        .sidebar-link i {
            font-size: 1.2rem;
            min-width: 30px;
            text-align: center;
        }

        .sidebar.collapsed .sidebar-link {
            justify-content: center;
            padding: 12px 0;
        }

        /* Profile Section */
        .profile-sidebar {
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid #e9ecef;
        }
        
        .profile-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: var(--customer-primary);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            margin: 0 auto 10px;
        }

        /* Main Content Adjustment */
        .main-content {
            margin-left: var(--sidebar-width);
            padding: 20px;
            flex: 1;
            transition: margin-left var(--transition-speed) ease;
        }

        .main-content.expanded {
            margin-left: var(--sidebar-collapsed-width);
        }

        .footer-main {
            margin-left: var(--sidebar-width);
            transition: margin-left var(--transition-speed) ease;
        }

        .footer-main.expanded {
            margin-left: var(--sidebar-collapsed-width);
        }

        #toggleBtn {
            cursor: pointer;
            border: none;
            background: none;
            color: var(--customer-primary);
            font-size: 1.2rem;
        }

        /* Responsive Mobile */
        @media (max-width: 768px) {
            .sidebar { left: -100%; }
            .sidebar.show-mobile { left: 0; width: var(--sidebar-width) !important; }
            .main-content, .footer-main { margin-left: 0 !important; }
        }
    </style>
</head>
<body>
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h6><i class="fas fa-car me-2"></i>HASTA</h6>
            <button id="toggleBtn"><i class="fas fa-bars"></i></button>
        </div>
        
        <div class="profile-sidebar">
            <div class="profile-avatar">{{ substr(auth()->user()->name, 0, 1) }}</div>
            <div class="profile-name small fw-bold">{{ auth()->user()->name }}</div>
            <div class="profile-status status-active small">Active</div>
        </div>
        
        <div class="sidebar-nav">
            @php $currentRoute = request()->route()->getName(); @endphp
            
            <a class="sidebar-link @if($currentRoute == 'customer.dashboard') active @endif" href="{{ route('customer.dashboard') }}">
                <i class="fas fa-tachometer-alt"></i>
                <span class="menu-text ms-2">Dashboard</span>
            </a>
            
            <a class="sidebar-link" href="#">
                <i class="fas fa-history"></i>
                <span class="menu-text ms-2">History</span>
            </a>

            <a class="sidebar-link @if($currentRoute == 'customer.inspections.index') active @endif" href="{{ route('customer.inspections.index') }}">
                <i class="fas fa-clipboard-list"></i>
                <span class="menu-text ms-2">Inspections</span>
            </a>
            
            <a class="sidebar-link" href="{{ route('browse.vehicle') }}" style="background-color: var(--customer-primary); color: white;">
                <i class="fas fa-calendar-plus"></i>
                <span class="menu-text ms-2">Book Now</span>
            </a>
            
            <div class="mt-4 pt-3 border-top">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="sidebar-link border-0 bg-transparent w-100 text-danger">
                        <i class="fas fa-sign-out-alt"></i>
                        <span class="menu-text ms-2">Logout</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <div class="main-content" id="mainContent">
        @yield('content')
    </div>
    
    <footer class="footer-main py-4 border-top bg-white" id="footerMain">
        <div class="container text-center text-muted small">
            &copy; {{ date('Y') }} Hasta Travel & Tours Sdn. Bhd.
        </div>
    </footer>
    
    <script>
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('mainContent');
        const footerMain = document.getElementById('footerMain');
        const toggleBtn = document.getElementById('toggleBtn');

        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');
            mainContent.classList.toggle('expanded');
            footerMain.classList.toggle('expanded');
        });
    </script>
    @stack('scripts')
</body>
</html>