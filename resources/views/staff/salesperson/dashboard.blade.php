@extends('layouts.salesperson')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salesperson Dashboard</title>
    <style>
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            margin: 0; 
            padding: 20px; 
            background-color: #f5f5f5; 
        }
        
        .container { 
            max-width: 1200px; 
            margin: 0 auto; 
        }
        
        .header { 
            background: linear-gradient(135deg, #e0a4b5ff 0%, #a24b5bff 100%);
            color: white; 
            padding: 30px; 
            border-radius: 10px; 
            margin-bottom: 30px; 
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        
        .stats { 
            display: flex; 
            gap: 20px; 
            flex-wrap: wrap; 
            margin-bottom: 30px; 
        }
        
        .stat-box { 
            background: white; 
            padding: 25px; 
            border-radius: 10px; 
            flex: 1; 
            min-width: 200px; 
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        
        .stat-box:hover { 
            transform: translateY(-5px); 
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .stat-box h3 { 
            margin-top: 0; 
            color: #333; 
            font-size: 16px; 
            font-weight: 600;
            margin-bottom: 10px;
        }
        
        .stat-box p { 
            font-size: 32px; 
            font-weight: bold; 
            margin: 0;
        }
        
        .stat-box small { 
            color: #666; 
            font-size: 12px; 
            display: block; 
            margin-top: 5px;
        }
        
        .card { 
            background: white; 
            padding: 25px; 
            border-radius: 10px; 
            margin-bottom: 30px; 
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 20px;
        }
        
        th { 
            background-color: #f8f9fa; 
            padding: 12px 15px; 
            text-align: left; 
            font-weight: 600;
            color: #495057;
            border-bottom: 2px solid #dee2e6;
        }
        
        td { 
            padding: 12px 15px; 
            border-bottom: 1px solid #dee2e6;
            color: #212529;
        }
        
        tr:hover { 
            background-color: #f8f9fa; 
        }
        
        .badge { 
            display: inline-block; 
            padding: 4px 8px; 
            border-radius: 4px; 
            font-size: 12px; 
            font-weight: 600;
        }
        
        .badge-pickup { 
            background-color: #e3f2fd; 
            color: #1976d2; 
        }
        
        .badge-return { 
            background-color: #f3e5f5; 
            color: #7b1fa2; 
        }
        
        .section-title { 
            color: #333; 
            margin-top: 0; 
            margin-bottom: 20px;
            font-size: 22px;
            font-weight: 600;
        }
        
        .sub-stats { 
            display: flex; 
            gap: 20px; 
            margin-top: 20px;
        }
        
        .sub-stat { 
            flex: 1; 
            text-align: center; 
            padding: 15px;
            border-radius: 8px;
        }
        
        .damage-free { 
            background-color: #e8f5e9; 
            color: #2e7d32; 
        }
        
        .damage-detected { 
            background-color: #ffebee; 
            color: #c62828; 
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- HEADER -->
        <div class="header">
            <h1 style="margin: 0; font-size: 28px;">Salesperson Dashboard</h1>
            <p style="margin: 10px 0 0; opacity: 0.9;">Vehicle Inspection Management System</p>
        </div>
        
        <!-- STATISTIK UTAMA -->
        <div class="stats">
            <div class="stat-box">
                <h3>Return Inspections</h3>
                <p style="color: #7b1fa2;">{{ $returnInspections }}</p>
                <small>Total inspections with type: return</small>
            </div>
            
            <div class="stat-box">
                <h3>Pickup Inspections</h3>
                <p style="color: #1976d2;">{{ $pickupInspections }}</p>
                <small>Total inspections with type: pickup</small>
            </div>
            
            <div class="stat-box">
                <h3>Total Inspections</h3>
                <p style="color: #333;">{{ $totalInspections }}</p>
                <small>All inspections recorded</small>
            </div>
        </div>
        
        <!-- STATISTIK TAMBAHAN -->
        <div class="stats">
            <div class="stat-box">
                <h3>Today's Inspections</h3>
                <p>{{ $todayInspections }}</p>
                <small>Inspections on {{ date('Y-m-d') }}</small>
            </div>
            
            <div class="stat-box">
                <h3>Pending Inspections</h3>
                <p>{{ $pendingInspectionsCount }}</p>
                <small>Inspections without remarks</small>
            </div>
        </div>
        
        <!-- DAMAGE STATISTICS -->
        <div class="card">
            <h2 class="section-title">Damage Statistics</h2>
            <div class="sub-stats">
                <div class="sub-stat damage-free">
                    <h3 style="margin: 0; color: #2e7d32;">Damage Free</h3>
                    <p style="font-size: 28px; margin: 10px 0;">{{ $damageFreeInspections }}</p>
                    <small>No damage detected</small>
                </div>
                
                <div class="sub-stat damage-detected">
                    <h3 style="margin: 0; color: #c62828;">Damage Detected</h3>
                    <p style="font-size: 28px; margin: 10px 0;">{{ $damageDetectedInspections }}</p>
                    <small>Damage reported</small>
                </div>
            </div>
        </div>
        
        <!-- RECENT INSPECTIONS -->
        <div class="card">
            <h2 class="section-title">Recent Inspections</h2>
            
            @if(isset($recentInspections) && $recentInspections->count() > 0)
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Type</th>
                            <th>Vehicle ID</th>
                            <th>Booking ID</th>
                            <th>Damage</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentInspections as $inspection)
                        <tr>
                            <td><strong>#{{ $inspection->inspectionID }}</strong></td>
                            <td>
                                <span class="badge badge-{{ $inspection->inspectionType }}">
                                    {{ ucfirst($inspection->inspectionType) }}
                                </span>
                            </td>
                            <td>Vehicle #{{ $inspection->vehicleID }}</td>
                            <td>{{ $inspection->bookingID ?? 'N/A' }}</td>
                            <td>
                                @if($inspection->damageDetected == 1)
                                    <span style="color: #c62828; font-weight: 600;">Yes</span>
                                @else
                                    <span style="color: #2e7d32;">No</span>
                                @endif
                            </td>
                            <td>{{ $inspection->created_at->format('Y-m-d H:i') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p style="text-align: center; color: #666; padding: 30px;">No recent inspections found.</p>
            @endif
        </div>
        
        <!-- FOOTER INFO -->
        <div style="text-align: center; margin-top: 40px; color: #666; font-size: 14px;">
            <p>Last updated: {{ date('Y-m-d H:i:s') }}</p>
            <p>Total records in system: {{ $totalInspections }} inspections</p>
        </div>
    </div>
    
    <script>
        // Tambahkan sedikit interaksi
        document.addEventListener('DOMContentLoaded', function() {
            // Animasi untuk stat-box
            const statBoxes = document.querySelectorAll('.stat-box');
            statBoxes.forEach((box, index) => {
                box.style.opacity = '0';
                box.style.transform = 'translateY(20px)';
                
                setTimeout(() => {
                    box.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                    box.style.opacity = '1';
                    box.style.transform = 'translateY(0)';
                }, index * 100);
            });
        });
    </script>
</body>
</html>