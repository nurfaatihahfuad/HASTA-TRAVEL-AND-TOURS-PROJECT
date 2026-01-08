<!--IT Admin Navbar-->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') - HASTA</title>
    
    
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
        .section-card { background: linear-gradient(135deg, #ffffff, #f8f9fa); padding: 25px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.08); border: 1px solid #e3e3e3; transition: all 0.3s ease; }
        
        .section-card:hover { box-shadow: 0 6px 16px rgba(0,0,0,0.12); }
        
        .metric-card { background: linear-gradient(135deg, #ffffff, #f8f9fa); padding: 20px; border-radius: 10px; border: 1px solid #dee2e6; box-shadow: 0 2px 8px rgba(0,0,0,0.05); text-align: center; transition: all 0.3s ease; } 
        
        .metric-card:hover { box-shadow: 0 4px 12px rgba(0,0,0,0.1); } .metric-title { font-size: 0.95rem; color: #6c757d; margin-bottom: 6px; font-weight: 500; } 
        
        .metric-value { font-size: 2.2rem; font-weight: 700; color: #212529; }
        
        .card-title {
            font-weight: 600;
            color: #dc3545;
        }

        .card-body small {
            font-size: 0.85rem;
        }

        .metric-delta {
            font-size: 0.8rem;
            color: #28a745;
        }

        .table th, .table td {
            vertical-align: middle;
        }

        .table td img {
            object-fit: cover;
            height: 60px;
            width: 80px;
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

        .filter-select {
        background: linear-gradient(135deg, #ffffff, #f8f9fa);
        border: 2px solid #dc3545; /* merah brand */
        color: #212529; /* tulisan gelap */
        font-weight: 500;
        transition: all 0.3s ease;
        }

        .filter-select:focus {
            box-shadow: 0 0 8px rgba(220,53,69,0.4);
            border-color: #dc3545;
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
            
            <a class="sidebar-link @if($currentRoute == 'admin_it.dashboard') active @endif" 
               href="{{ route('admin_it.dashboard') }}">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
            
            <a class="sidebar-link @if($currentRoute == 'customer.index') active @endif" 
               href="#">
                <i class="fas fa-users"></i> Booking
            </a>

            <a class="sidebar-link @if($currentRoute == 'customer.index') active @endif" 
               href="#">
                <i class="fas fa-users"></i> Customer Information
            </a>

            <a class="sidebar-link @if($currentRoute == 'customer.index') active @endif" 
               href="#">
                <i class="fas fa-users"></i> Blacklisted Customer
            </a>
            
            <a class="sidebar-link @if(str_contains($currentRoute, 'staff.')) active @endif" 
               href="{{ route('staff.index') }}">
                <i class="fas fa-user-tie"></i> Staff Management
            </a>

            <a class="sidebar-link @if(str_contains($currentRoute, 'admins.')) active @endif" 
               href="{{ route('admins.index') }}">
                <i class='fas fa-user-shield'></i> Admin Management
            </a>

            <a class="sidebar-link @if($currentRoute == 'vehicles.index') active @endif" 
                href="{{ route('vehicles.index') }}">
                <i class="fas fa-car"></i> Vehicle Management
            </a>

            <a class="sidebar-link" href="#">
                <i class="fas fa-clipboard-check"></i> Car Inspection
            </a>
            
            <a class="sidebar-link @if(str_contains($currentRoute, 'reports.')) active @endif" 
                href="{{ route('reports.index') }}">
                <i class="fas fa-chart-bar"></i> Report
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
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- jsPDF + AutoTable untuk PDF export -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.25/jspdf.plugin.autotable.min.js"></script>

    <!-- SheetJS untuk Excel export -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    
    <script>
    function exportTableToPDF() {
        const doc = new window.jspdf.jsPDF();
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