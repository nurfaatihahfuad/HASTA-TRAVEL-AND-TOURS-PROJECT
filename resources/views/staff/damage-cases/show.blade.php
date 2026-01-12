@extends('layouts.salesperson')

@section('title', 'Case Details #' . $damageCase->caseID)

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <a href="{{ route('staff.damage-cases.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Back to List
        </a>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="section-card mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h3 class="h5 mb-0">Damage Case Information</h3>
                    <span class="badge {{ $damageCase->status == 'resolved' ? 'bg-success' : 'bg-warning' }}">
                        {{ strtoupper($damageCase->status) }}
                    </span>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-6 mb-3">
                        <label class="text-muted d-block small">Vehicle</label>
                        <strong>{{ $damageCase->vehicle->brand }} ({{ $damageCase->vehicle->plateNumber }})</strong>
                    </div>
                    <div class="col-sm-6 mb-3">
                        <label class="text-muted d-block small">Customer Name</label>
                        <strong>{{ $damageCase->customer->name }}</strong>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="text-muted d-block small">Resolution Notes</label>
                        <p class="bg-light p-3 rounded border">
                            {{ $damageCase->resolution_notes ?? 'No notes recorded yet.' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="section-card">
                <h3 class="h5 mb-3">Financial & Staff</h3>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between px-0">
                        <span class="text-muted">Estimated Cost:</span>
                        <span class="fw-bold">RM {{ number_format($damageCase->actual_cost, 2) }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between px-0">
                        <span class="text-muted">Assigned To:</span>
                        <span>{{ $damageCase->assignedStaff->name ?? 'Unassigned' }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between px-0">
                        <span class="text-muted">Report Date:</span>
                        <span>{{ $damageCase->created_at->format('d/m/Y') }}</span>
                    </li>
                </ul>
                <div class="mt-4">
                    <a href="{{ route('staff.damage-cases.edit', $damageCase->caseID) }}" class="btn btn-primary w-100">
                        <i class="fas fa-edit me-1"></i> Update Status
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection