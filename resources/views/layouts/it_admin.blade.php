<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard') - HASTA</title>
    
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
        }

        /* Hide text when collapsed */
        .sidebar.collapsed .sidebar-header h5,
        .sidebar.collapsed .menu-text,
        .sidebar.collapsed hr {
            display: none;
        }

        .sidebar-nav {
            padding: 10px;
            overflow-y: auto;
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
            background-color: #fff1f2;
            color: var(--primary-color);
        }
        
        .sidebar-link i {
            font-size: 1.1rem;
            min-width: 35px;
            text-align: center;
        }

        .sidebar.collapsed .sidebar-link {
            justify-content: center;
            padding: 12px 0;
        }

        /* Main content */
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
            border: none;
            background: none;
            color: #6c757d;
            font-size: 1.2rem;
        }

        /* Elements from your IT Admin specific style */
        .section-card { background: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.08); border: 1px solid #e3e3e3; margin-bottom: 20px; }
        .metric-card { background: white; padding: 20px; border-radius: 10px; border: 1px solid #dee2e6; text-align: center; }
        .metric-value { font-size: 2.2rem; font-weight: 700; }

        @media (max-width: 768px) {
            .sidebar { left: -100%; }
            .sidebar.show-mobile { left: 0; width: var(--sidebar-width) !important; }
            .main-content { margin-left: 0 !important; }
        }
    </style>
</head>
<body>
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h5><i class="fas fa-car me-2"></i>HASTA</h5>
            <button id="toggleBtn"><i class="fas fa-bars"></i></button>
        </div>
        
        <div class="sidebar-nav">
            @php $currentRoute = request()->route()->getName(); @endphp
            
            <a class="sidebar-link @if($currentRoute == 'admin_it.dashboard') active @endif" href="{{ route('admin_it.dashboard') }}">
                <i class="fas fa-tachometer-alt"></i>
                <span class="menu-text">Dashboard</span>
            </a>

            <a class="sidebar-link @if($currentRoute == 'admin.bookings.index') active @endif" href="{{ route('admin.bookings.index') }}">
                <i class="fas fa-calendar-check"></i>
                <span class="menu-text">Booking</span>
            </a>

            <a class="sidebar-link @if($currentRoute == 'admin.customers.index') active @endif" href="{{ route('admin.customers.index') }}">
                <i class="fa fa-drivers-license"></i>
                <span class="menu-text">Customers</span>
            </a>

            <a class="sidebar-link @if($currentRoute == 'admin.blacklisted.index') active @endif" href="{{ route('admin.blacklisted.index') }}">
                <i class="fa fa-user-times"></i>
                <span class="menu-text">Blacklist</span>
            </a>
            
            <a class="sidebar-link @if(str_contains($currentRoute, 'staff.')) active @endif" href="{{ route('staff.index') }}">
                <i class="fas fa-user-tie"></i>
                <span class="menu-text">Staff Management</span>
            </a>

            <a class="sidebar-link @if(str_contains($currentRoute, 'admins.')) active @endif" href="{{ route('admins.index') }}">
                <i class='fas fa-user-shield'></i>
                <span class="menu-text">Admin Management</span>
            </a>

            
                <a class="sidebar-link @if(request()->routeIs('admin.commissionVerify.*')) active @endif" 
                    href="{{ route('admin.commissionVerify.index') }}">
                    <i class="fas fa-money-bill"></i>
                    <span class="menu-text">Commission Verification</span>
                </a>
            

            <a class="sidebar-link @if($currentRoute == 'vehicles.index') active @endif" href="{{ route('vehicles.index') }}">
                <i class="fas fa-car"></i>
                <span class="menu-text">Vehicles</span>
            </a>

            <a class="sidebar-link" href="#">
                <i class="fas fa-clipboard-check"></i>
                <span class="menu-text">Inspections</span>
            </a>
            
            <a class="sidebar-link @if(str_contains($currentRoute, 'reports.')) active @endif" href="{{ route('reports.index') }}">
                <i class="fas fa-chart-bar"></i>
                <span class="menu-text">Reports</span>
            </a>
            
            <hr>
            
            <form method="POST" action="{{ route('logout') }}" class="mt-2 px-2">
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.25/jspdf.plugin.autotable.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    
    <script>
        // Toggle Sidebar Logic
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('mainContent');
        const toggleBtn = document.getElementById('toggleBtn');

        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');
            mainContent.classList.toggle('expanded');
        });

        // Your existing Export Functions
        function exportTableToPDF() {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();
            doc.text("Total Booking Report", 14, 15);
            const table = document.getElementById('bookingTable');
            if (!table) { alert("Table not found"); return; }
            doc.autoTable({ html: '#bookingTable' });
            doc.save('total_booking_report.pdf');
        }

        function exportTableToExcel() {
            const table = document.getElementById("bookingTable");
            if (!table) { alert("Table not found"); return; }
            const wb = XLSX.utils.table_to_book(table, { sheet: "Report" });
            XLSX.writeFile(wb, "total_booking_report.xlsx");
        }
    </script>
    @stack('scripts')
</body>
</html>