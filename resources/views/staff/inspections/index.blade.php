@extends('layouts.salesperson')

@section('title', 'Staff - Inspections')

@section('styles')
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
@endsection

@section('content')
<div class="container-fluid">
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

    <!-- Filter Section (Optional) -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-filter"></i> Filter Inspections
            </h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('staff.inspections.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Inspection Type</label>
                    <select name="type" class="form-select">
                        <option value="">All Types</option>
                        <option value="pickup" {{ request('type') == 'pickup' ? 'selected' : '' }}>Pickup</option>
                        <option value="return" {{ request('type') == 'return' ? 'selected' : '' }}>Return</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Damage Status</label>
                    <select name="damage" class="form-select">
                        <option value="">All</option>
                        <option value="yes" {{ request('damage') == 'yes' ? 'selected' : '' }}>With Damage</option>
                        <option value="no" {{ request('damage') == 'no' ? 'selected' : '' }}>No Damage</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Date</label>
                    <input type="date" name="date" class="form-control" value="{{ request('date') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Search</label>
                    <input type="text" name="search" class="form-control" placeholder="Booking/Staff/Vehicle ID" value="{{ request('search') }}">
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Apply Filters
                    </button>
                    <a href="{{ route('staff.inspections.index') }}" class="btn btn-secondary">
                        <i class="fas fa-redo"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Inspections Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-table"></i> Inspections List
            </h6>
            <div>
                <a href="{{ route('staff.inspections.today') }}" class="btn btn-sm btn-info">
                    <i class="fas fa-calendar-day"></i> Today's
                </a>
                <a href="{{ route('staff.inspections.pending') }}" class="btn btn-sm btn-warning">
                    <i class="fas fa-clock"></i> Pending
                </a>
                <a href="{{ route('staff.inspections.damage') }}" class="btn btn-sm btn-danger">
                    <i class="fas fa-exclamation-triangle"></i> With Damage
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead class="thead-light">
                        <tr>
                            <th>#</th>
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
                        @forelse($inspections as $index => $inspection)
                        <tr>
                            <td>{{ $loop->iteration + (($inspections->currentPage() - 1) * $inspections->perPage()) }}</td>
                            <td>
                                <strong>{{ $inspection->bookingID ?? 'N/A' }}</strong>
                            </td>
                            <td>
                                @if($inspection->vehicle)
                                    {{ $inspection->vehicle->vehicleName ?? 'Vehicle #' . $inspection->vehicleID }}
                                    <br><small class="text-muted">{{ $inspection->vehicle->plateNo ?? '' }}</small>
                                @else
                                    <span class="text-muted">Vehicle #{{ $inspection->vehicleID }}</span>
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
                                <small>{{ optional($inspection->created_at)->format('d/m/Y') ?? 'N/A' }}</small><br>
                                <small class="text-muted">{{ optional($inspection->created_at)->format('h:i A') ?? '' }}</small>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('staff.inspections.show', $inspection->id ?? $inspection->inspectionID) }}" 
                                       class="btn btn-outline-primary" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-4">
                                <i class="fas fa-inbox fa-2x text-muted mb-3"></i>
                                <p class="text-muted">No inspections found</p>
                                <a href="{{ route('staff.inspections.index') }}" class="btn btn-sm btn-primary">
                                    Clear Filters
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($inspections instanceof \Illuminate\Pagination\LengthAwarePaginator && $inspections->hasPages())
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div class="text-muted">
                    Showing {{ $inspections->firstItem() ?? 0 }} to {{ $inspections->lastItem() ?? 0 }} of {{ $inspections->total() }} entries
                </div>
                <div>
                    <nav>
                        <ul class="pagination mb-0">
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
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Simple JavaScript for interactivity
    document.addEventListener('DOMContentLoaded', function() {
        // Enable tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
        
        // Filter form auto-submit on change (optional)
        document.querySelectorAll('.form-select, .form-control').forEach(element => {
            element.addEventListener('change', function() {
                if(this.name !== 'search') { // Don't auto-submit on search typing
                    this.form.submit();
                }
            });
        });
        
        // Search with delay
        let searchTimeout;
        document.querySelector('input[name="search"]')?.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                this.form.submit();
            }, 500);
        });
    });
</script>
@endsection