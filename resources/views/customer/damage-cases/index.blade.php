@extends('layouts.customer')

@section('title', 'My Damage Cases')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">
            <i class="fas fa-exclamation-triangle"></i> My Damage Cases
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
                                Total Cases</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalCases }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-file-alt fa-2x text-gray-300"></i>
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
                                Pending</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $pendingCases }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
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
                                Resolved</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $resolvedCases }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Damage Cases Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-table"></i> My Damage Cases History
            </h6>
        </div>
        <div class="card-body">
            @if($damageCases->count() > 0)
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th>Case ID</th>
                            <th>Vehicle</th>
                            <th>Damage Type</th>
                            <th>Severity</th>
                            <th>Status</th>
                            <th>Reported Date</th>
                            <th>Assigned To</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($damageCases as $case)
                        <tr>
                            <td><strong>{{ $case->caseID }}</strong></td>
                            <td>
                                {{ $case->vehicle->vehicleName ?? 'N/A' }}
                                <br><small class="text-muted">{{ $case->vehicle->plateNo ?? '' }}</small>
                            </td>
                            <td>{{ $case->damageType }}</td>
                            <td>
                                <span class="badge bg-{{ 
                                    $case->severity == 'high' ? 'danger' : 
                                    ($case->severity == 'medium' ? 'warning' : 'info') 
                                }}">
                                    {{ ucfirst($case->severity) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-{{ 
                                    $case->status == 'resolved' ? 'success' : 
                                    ($case->status == 'investigating' ? 'warning' : 'secondary') 
                                }}">
                                    {{ ucfirst($case->status) }}
                                </span>
                            </td>
                            <td>{{ $case->reportedDate->format('d/m/Y') }}</td>
                            <td>
                                @if($case->assignedStaff)
                                    {{ $case->assignedStaff->name }}
                                @else
                                    <span class="text-muted">Not assigned</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('customer.damage-cases.show', $case->caseID) }}" 
                                   class="btn btn-sm btn-primary">
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
                {{ $damageCases->links() }}
            </div>
            
            @else
            <div class="text-center py-5">
                <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                <h5 class="text-muted">No Damage Cases Found</h5>
                <p class="text-muted">You have no reported damage cases.</p>
                <a href="{{ route('customer.inspections.index') }}" class="btn btn-primary mt-2">
                    <i class="fas fa-clipboard-check"></i> View My Inspections
                </a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection