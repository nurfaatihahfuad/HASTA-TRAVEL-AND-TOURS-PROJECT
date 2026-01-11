@extends('layouts.customer')

@section('title', 'My Vehicle Inspections')

@section('styles')
<style>
    .inspection-card {
        border-left: 4px solid #4e73df;
        transition: all 0.3s;
    }
    .inspection-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    .type-badge {
        font-size: 0.8rem;
        padding: 0.25em 0.75em;
    }
    .condition-badge {
        font-size: 0.8rem;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">
            <i class="fas fa-clipboard-check"></i> My Vehicle Inspections
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
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $pickupCount ?? 0}}</div>
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
                                Damage Reports</div>
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

    <!-- Inspections List -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-table"></i> My Inspection History
            </h6>
        </div>
        <div class="card-body">
            @if(isset($inspections) && $inspections->count() > 0)
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="inspectionsTable" width="100%" cellspacing="0">
                    <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th>Inspection ID</th>
                            <th>Vehicle</th>
                            <th>Type</th>
                            <th>Condition</th>
                            <th>Damage</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($inspections as $index => $inspection)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td><strong>{{ $inspection->inspectionID ?? 'N/A' }}</strong></td>
                            <td>
                                @if($inspection->vehicle)
                                {{ $inspection->vehicle->vehicleName }}
                                <br><small class="text-muted">{{ $inspection->vehicle->plateNo }}</small>
                                @else
                                <span class="text-muted">Vehicle #{{ $inspection->vehicleID }}</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge type-badge {{ $inspection->inspectionType == 'pickup' ? 'bg-info' : 'bg-warning' }}">
                                    {{ strtoupper($inspection->inspectionType) }}
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
                                <span class="badge condition-badge bg-{{ $badgeClass }}">
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
                                <small>{{ optional($inspection->created_at)->format('d/m/Y') ?? 'N/A' }}</small><br>
                                <small class="text-muted">{{ optional($inspection->created_at)->format('h:i A') ?? '' }}</small>
                            </td>
                            <td>
                                <a href="{{ route('customer.inspections.show', $inspection->inspectionID) }}" 
                                   class="btn btn-sm btn-primary" title="View Details">
                                    <i class="fas fa-eye"></i> View
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $inspections->links() }}
            </div>
            
            @else
            <div class="text-center py-5">
                <i class="fas fa-clipboard-check fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No inspections found</h5>
                <p class="text-muted">You haven't had any vehicle inspections yet.</p>
                <a href="{{ route('browse.vehicle') }}" class="btn btn-primary mt-2">
                    <i class="fas fa-car"></i> Book a Vehicle
                </a>
            </div>
            @endif
        </div>
    </div>

    <!-- Recent Bookings with Inspections -->
    @if(isset($bookings) && $bookings->count() > 0)
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-calendar-alt"></i> My Recent Bookings
            </h6>
        </div>
        <div class="card-body">
            <div class="row">
                @foreach($bookings->take(3) as $booking)
                <div class="col-md-4 mb-3">
                    <div class="card inspection-card h-100">
                        <div class="card-header">
                            <h6 class="mb-0">
                                Booking #{{ $booking->bookingID }}
                                <span class="badge bg-{{ $booking->bookingStatus == 'successful' ? 'success' : 'warning' }} float-end">
                                    {{ ucfirst($booking->bookingStatus) }}
                                </span>
                            </h6>
                        </div>
                        <div class="card-body">
                            @if($booking->vehicle)
                            <h6 class="card-title">{{ $booking->vehicle->vehicleName }}</h6>
                            <p class="card-text text-muted mb-1">
                                <i class="fas fa-calendar"></i> 
                                {{ optional($booking->pickup_dateTime)->format('d/m/Y') }} - 
                                {{ optional($booking->return_dateTime)->format('d/m/Y') }}
                            </p>
                            @endif
                            
                            @if($booking->inspections->count() > 0)
                            <div class="mt-3">
                                <h6 class="text-muted mb-2">Inspections:</h6>
                                @foreach($booking->inspections as $inspection)
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span>
                                        <span class="badge {{ $inspection->inspectionType == 'pickup' ? 'bg-info' : 'bg-warning' }}">
                                            {{ ucfirst($inspection->inspectionType) }}
                                        </span>
                                    </span>
                                    <a href="{{ route('customer.inspections.show', $inspection->inspectionID) }}" 
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                                @endforeach
                            </div>
                            @else
                            <div class="text-center text-muted mt-3">
                                <i class="fas fa-clipboard fa-2x"></i>
                                <p class="mt-2 mb-0">No inspections yet</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize DataTable
        $('#inspectionsTable').DataTable({
            pageLength: 10,
            order: [[6, 'desc']], // Sort by date descending
            responsive: true
        });
    });
</script>
@endsection