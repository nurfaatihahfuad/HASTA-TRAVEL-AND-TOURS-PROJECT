@extends('layouts.salesperson')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff - Inspections</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<style>
    /* 1. Menambah garisan warna di atas kad (Accent Borders) */
    .border-left-primary { border-left: 0.25rem solid #4e73df !important; }
    .border-left-success { border-left: 0.25rem solid #1cc88a !important; }
    .border-left-info { border-left: 0.25rem solid #36b9cc !important; }
    .border-left-warning { border-left: 0.25rem solid #f6c23e !important; }
    
    /* Highlight khusus untuk bahagian Damage (Merah) */
    .card.border-left-warning {
        border-left: 0.25rem solid #e74a3b !important; /* Tukar ke merah */
    }
    .text-warning {
        color: #e74a3b !important; /* Tulisan damage jadi merah */
    }

    /* 2. Gaya untuk Table - Hover effect warna merah lembut */
    .table-hover tbody tr:hover {
        background-color: rgba(231, 74, 59, 0.05); /* Merah sangat cair */
        transition: 0.3s;
    }

    /* 3. Button Customization */
    .btn-outline-danger:hover {
        background-color: #e74a3b;
        color: white;
    }

    /* 4. Soft Shadow & Rounding untuk nampak Moden */
    .card {
        border: none;
        border-radius: 10px;
        transition: transform 0.2s ease-in-out;
    }
    
    .card:hover {
        transform: translateY(-5px); /* Kad naik sikit bila hover */
    }

    /* 5. Badge Styling */
    .badge {
        padding: 0.5em 0.8em;
        border-radius: 5px;
        font-weight: 500;
    }

    /* 6. Dashboard Header Icon */
    .fa-clipboard-check {
        color: #e74a3b; /* Warna ikon utama jadi merah */
    }
</style>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Main content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
                <!-- Header -->
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">
                        <i class="fas fa-clipboard-check"></i> Inspections Record Checklist
                    </h1>
                </div>

                <!-- Stats Cards -->
                <div class="row mb-4">
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-primary shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                            Total Inspections</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalInspections ?? 0 }}</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-success shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                            Pickup Inspections</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $pickupCount ?? 0 }}</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-car fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-info shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                            Return Inspections</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $returnCount ?? 0 }}</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-undo fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-warning shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                            Damage Detected</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $damageCount ?? 0 }}</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Inspections Table -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-table"></i> Recent Inspections
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <thead class="thead-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Booking ID</th>
                                        <th>Vehicle</th>
                                        <th>Type</th>
                                        <th>Condition</th>
                                        <th>Damage</th>
                                        <th>Staff</th>
                                        <th>Date & Time</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($inspections as $inspection)
                                    <tr>
                                        <td>#{{ $inspection->id ?? $inspection->inspectionID ?? 'N/A' }}</td>
                                        <td>
                                            <strong>{{ $inspection->bookingID ?? 'N/A' }}</strong>
                                        </td>
                                        <td>
                                            @if($inspection->vehicleID)
                                                Vehicle #{{ $inspection->vehicleID }}
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge {{ $inspection->inspectionType == 'pickup' ? 'bg-info' : 'bg-warning' }}">
                                                {{ strtoupper($inspection->inspectionType ?? 'N/A') }}
                                            </span>
                                        </td>
                                        <td>
                                            @php
                                                $condition = $inspection->carCondition ?? 'unknown';
                                                $badgeClass = [
                                                    'excellent' => 'success',
                                                    'good' => 'primary', 
                                                    'fair' => 'warning',
                                                    'poor' => 'danger'
                                                ][$condition] ?? 'secondary';
                                            @endphp
                                            <span class="badge bg-{{ $badgeClass }}">
                                                {{ ucfirst($condition) }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            @if($inspection->damageDetected)
                                                <span class="badge bg-danger">
                                                    <i class="fas fa-exclamation-triangle"></i> Yes
                                                </span>
                                            @else
                                                <span class="badge bg-success">
                                                    <i class="fas fa-check"></i> No
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <small>{{ $inspection->staffID ?? 'N/A' }}</small>
                                        </td>
                                        <td>
                                            <small>{{ $inspection->created_at->format('d/m/Y') ?? 'N/A' }}</small><br>
                                            <small class="text-muted">{{ $inspection->created_at->format('h:i A') ?? '' }}</small>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <button class="btn btn-outline-primary">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button class="btn btn-outline-warning">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-4">
                                            <i class="fas fa-inbox fa-2x text-muted mb-3"></i>
                                            <p class="text-muted">No inspections found</p>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        @if($inspections instanceof \Illuminate\Pagination\LengthAwarePaginator && $inspections->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            <nav>
                                <ul class="pagination">
                                    @if($inspections->onFirstPage())
                                    <li class="page-item disabled"><span class="page-link">Previous</span></li>
                                    @else
                                    <li class="page-item"><a class="page-link" href="{{ $inspections->previousPageUrl() }}">Previous</a></li>
                                    @endif
                                    
                                    @for($i = 1; $i <= $inspections->lastPage(); $i++)
                                    <li class="page-item {{ $inspections->currentPage() == $i ? 'active' : '' }}">
                                        <a class="page-link" href="{{ $inspections->url($i) }}">{{ $i }}</a>
                                    </li>
                                    @endfor
                                    
                                    @if($inspections->hasMorePages())
                                    <li class="page-item"><a class="page-link" href="{{ $inspections->nextPageUrl() }}">Next</a></li>
                                    @else
                                    <li class="page-item disabled"><span class="page-link">Next</span></li>
                                    @endif
                                </ul>
                            </nav>
                        </div>
                        @endif
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Simple JavaScript for interactivity
        document.addEventListener('DOMContentLoaded', function() {
            // Enable tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
            
            // Add click handlers to buttons
            document.querySelectorAll('.btn-outline-primary').forEach(button => {
                button.addEventListener('click', function() {
                    alert('View inspection details');
                });
            });
            
            document.querySelectorAll('.btn-outline-warning').forEach(button => {
                button.addEventListener('click', function() {
                    alert('Edit inspection');
                });
            });
        });
    </script>
</body>
</html>