{{-- resources/views/staff/inspections/index.blade.php --}}
@extends('layouts.salesperson')

@section('title', 'All Inspections')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-clipboard-check text-primary"></i> All Inspections
            </h1>
            <p class="mb-0">Manage and view all vehicle inspections</p>
        </div>
        <div>
            <a href="{{ route('staff.inspections.today') }}" class="btn btn-info">
                <i class="fas fa-calendar-day"></i> Today's Inspections
            </a>
            <a href="{{ route('staff.dashboard') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
        </div>
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
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $inspections->total() }}</div>
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
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $pickupCount }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-car fa-2x text-gray-300"></i>
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
                                Return Inspections</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $returnCount }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-undo fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                With Damage</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $damageCount }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-filter"></i> Filter Inspections
            </h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('staff.inspections.index') }}" class="row">
                <div class="col-md-3 mb-3">
                    <label>Inspection Type</label>
                    <select name="type" class="form-control">
                        <option value="">All Types</option>
                        <option value="pickup" {{ request('type') == 'pickup' ? 'selected' : '' }}>Pickup</option>
                        <option value="return" {{ request('type') == 'return' ? 'selected' : '' }}>Return</option>
                    </select>
                </div>
                
                <div class="col-md-3 mb-3">
                    <label>Damage Status</label>
                    <select name="damage" class="form-control">
                        <option value="">All</option>
                        <option value="yes" {{ request('damage') == 'yes' ? 'selected' : '' }}>With Damage</option>
                        <option value="no" {{ request('damage') == 'no' ? 'selected' : '' }}>No Damage</option>
                    </select>
                </div>
                
                <div class="col-md-3 mb-3">
                    <label>Date Range</label>
                    <input type="date" name="date" class="form-control" value="{{ request('date') }}">
                </div>
                
                <div class="col-md-3 mb-3">
                    <label>Search</label>
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" 
                               placeholder="Booking ID or Staff ID" value="{{ request('search') }}">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Inspections Table -->
    <div class="card shadow">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-table"></i> Inspections List
            </h6>
            <div>
                <span class="badge badge-info">Pickup</span>
                <span class="badge badge-warning">Return</span>
                <span class="badge badge-danger">Damage</span>
            </div>
        </div>
        <div class="card-body">
            @if($inspections->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
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
                            @foreach($inspections as $inspection)
                            <tr>
                                <td>#{{ $inspection->id }}</td>
                                <td>
                                    <a href="{{ route('booking.summary', $inspection->bookingID) }}" 
                                       class="text-primary font-weight-bold">
                                        {{ $inspection->bookingID }}
                                    </a>
                                </td>
                                <td>
                                    @if($inspection->vehicle)
                                        Vehicle #{{ $inspection->vehicleID }}
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge badge-{{ $inspection->inspectionType == 'pickup' ? 'info' : 'warning' }}">
                                        {{ strtoupper($inspection->inspectionType) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge 
                                        {{ $inspection->carCondition == 'excellent' ? 'badge-success' : '' }}
                                        {{ $inspection->carCondition == 'good' ? 'badge-primary' : '' }}
                                        {{ $inspection->carCondition == 'fair' ? 'badge-warning' : '' }}
                                        {{ $inspection->carCondition == 'poor' ? 'badge-danger' : '' }}">
                                        {{ ucfirst($inspection->carCondition) }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    @if($inspection->damageDetected)
                                        <span class="badge badge-danger" data-toggle="tooltip" 
                                              title="Damage reported">
                                            <i class="fas fa-exclamation-triangle"></i> Yes
                                        </span>
                                    @else
                                        <span class="badge badge-success">
                                            <i class="fas fa-check"></i> No
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <small>{{ $inspection->staffID }}</small>
                                    <br>
                                    <small class="text-muted">
                                        {{ $inspection->created_at->format('d/m') }}
                                    </small>
                                </td>
                                <td>
                                    {{ $inspection->created_at->format('d/m/Y') }}
                                    <br>
                                    <small class="text-muted">
                                        {{ $inspection->created_at->format('h:i A') }}
                                    </small>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('staff.inspections.show', $inspection->id) }}" 
                                           class="btn btn-sm btn-info" data-toggle="tooltip" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('staff.inspections.edit', $inspection->id) }}" 
                                           class="btn btn-sm btn-warning" data-toggle="tooltip" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @if($inspection->damageDetected)
                                            @php
                                                $damageCase = \App\Models\DamageCase::where('inspectionID', $inspection->inspectionID)->first();
                                            @endphp
                                            @if($damageCase)
                                                <a href="{{ route('damagecase.show', $damageCase->caseID) }}" 
                                                   class="btn btn-sm btn-danger" data-toggle="tooltip" title="Damage Case">
                                                    <i class="fas fa-wrench"></i>
                                                </a>
                                            @endif
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $inspections->appends(request()->query())->links() }}
                </div>
                
                <!-- Summary -->
                <div class="mt-3 text-muted">
                    Showing {{ $inspections->firstItem() }} to {{ $inspections->lastItem() }} 
                    of {{ $inspections->total() }} inspections
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-clipboard-list fa-4x text-muted mb-3"></i>
                    <h4 class="text-muted">No inspections found</h4>
                    <p class="text-muted">Try adjusting your filters or check back later.</p>
                    <a href="{{ route('staff.inspections.index') }}" class="btn btn-primary">
                        <i class="fas fa-redo"></i> Reset Filters
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Quick Actions Card -->
    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-bolt"></i> Quick Actions
                    </h6>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <a href="{{ route('staff.inspections.today') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-calendar-day text-info"></i> Today's Inspections
                            <span class="badge badge-info float-right">{{ $todayCount }}</span>
                        </a>
                        <a href="{{ route('staff.inspections.pending') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-clock text-warning"></i> Pending Verification
                            <span class="badge badge-warning float-right">{{ $pendingCount }}</span>
                        </a>
                        <a href="{{ route('staff.inspections.damage') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-exclamation-triangle text-danger"></i> With Damage Reports
                            <span class="badge badge-danger float-right">{{ $damageCount }}</span>
                        </a>
                        <a href="{{ route('staff.dashboard') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-tachometer-alt text-success"></i> Back to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-info text-white">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-chart-pie"></i> Inspection Statistics
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <canvas id="typeChart" width="200" height="200"></canvas>
                        </div>
                        <div class="col-md-6">
                            <div class="mt-3">
                                <h6>Condition Distribution:</h6>
                                <div class="mb-2">
                                    <span class="badge badge-success">Excellent: {{ $excellentCount }}</span>
                                    <div class="progress" style="height: 10px;">
                                        <div class="progress-bar bg-success" style="width: {{ $excellentPercentage }}%"></div>
                                    </div>
                                </div>
                                <div class="mb-2">
                                    <span class="badge badge-primary">Good: {{ $goodCount }}</span>
                                    <div class="progress" style="height: 10px;">
                                        <div class="progress-bar bg-primary" style="width: {{ $goodPercentage }}%"></div>
                                    </div>
                                </div>
                                <div class="mb-2">
                                    <span class="badge badge-warning">Fair: {{ $fairCount }}</span>
                                    <div class="progress" style="height: 10px;">
                                        <div class="progress-bar bg-warning" style="width: {{ $fairPercentage }}%"></div>
                                    </div>
                                </div>
                                <div class="mb-2">
                                    <span class="badge badge-danger">Poor: {{ $poorCount }}</span>
                                    <div class="progress" style="height: 10px;">
                                        <div class="progress-bar bg-danger" style="width: {{ $poorPercentage }}%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Initialize tooltips
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    });
    
    // Pie Chart for Inspection Types
    const typeCtx = document.getElementById('typeChart').getContext('2d');
    const typeChart = new Chart(typeCtx, {
        type: 'pie',
        data: {
            labels: ['Pickup', 'Return'],
            datasets: [{
                data: [{{ $pickupCount }}, {{ $returnCount }}],
                backgroundColor: [
                    '#36b9cc', // Info color
                    '#f6c23e'  // Warning color
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                },
                title: {
                    display: true,
                    text: 'Inspection Types'
                }
            }
        }
    });
    
    // Auto-refresh every 30 seconds if on today's page
    @if(request()->has('today') || request()->is('staff/inspections/today'))
    setTimeout(function() {
        window.location.reload();
    }, 30000); // 30 seconds
    @endif
</script>
@endpush

@push('styles')
<style>
    .table-hover tbody tr:hover {
        background-color: rgba(0, 123, 255, 0.05);
    }
    .badge {
        font-size: 0.85em;
        padding: 0.35em 0.65em;
    }
    .btn-group .btn {
        margin-right: 2px;
    }
    .progress {
        border-radius: 10px;
    }
</style>
@endpush