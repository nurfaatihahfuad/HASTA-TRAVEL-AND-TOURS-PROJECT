@extends('layouts.it_admin') {{-- Tukar ke layout admin anda --}}

@section('title', 'Admin - Car Inspections Management')

@section('styles')
<style>
    /* Kekalkan gaya CSS anda yang cantik itu */
    .border-left-primary { border-left: 0.25rem solid #4e73df !important; }
    .border-left-success { border-left: 0.25rem solid #1cc88a !important; }
    .border-left-info { border-left: 0.25rem solid #36b9cc !important; }
    .border-left-danger { border-left: 0.25rem solid #e74a3b !important; }
    
    .card {
        border: none;
        border-radius: 10px;
        transition: transform 0.2s ease-in-out;
    }
    
    .card:hover {
        transform: translateY(-5px);
    }

    .table-hover tbody tr:hover {
        background-color: rgba(231, 74, 59, 0.05);
    }

    .badge {
        padding: 0.5em 0.8em;
        border-radius: 5px;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">
            <i class="fas fa-clipboard-check text-danger"></i> Admin: Inspection Management
        </h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            {{-- Tambah butang export jika perlu untuk admin --}}
            <button type="button" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-download"></i> Export Report
            </button>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Inspections (Global)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalInspections ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-history fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Pickups Today</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $pickupCount ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-car-side fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Returns Today</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $returnCount ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-undo-alt fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Critical Damage</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $damageCount ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-filter"></i> Search & Filter (Admin)</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.inspections.index') }}" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Search Everything</label>
                    <input type="text" name="search" class="form-control" placeholder="Search Staff, Vehicle, or Booking ID..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Type</label>
                    <select name="type" class="form-select" onchange="this.form.submit()">
                        <option value="">All Types</option>
                        <option value="pickup" {{ request('type') == 'pickup' ? 'selected' : '' }}>Pickup</option>
                        <option value="return" {{ request('type') == 'return' ? 'selected' : '' }}>Return</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Damage</label>
                    <select name="damage" class="form-select" onchange="this.form.submit()">
                        <option value="">All Status</option>
                        <option value="yes" {{ request('damage') == 'yes' ? 'selected' : '' }}>Damage Reported</option>
                        <option value="no" {{ request('damage') == 'no' ? 'selected' : '' }}>Clean/No Damage</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <a href="{{ route('admin.inspections.index') }}" class="btn btn-secondary w-100">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Master Inspection List</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="bg-light">
                        <tr>
                            <th>#</th>
                            <th>Vehicle Details</th>
                            <th>Type</th>
                            <th>Condition</th>
                            <th>Damage</th>
                            <th>Inspected By (Staff)</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($inspections as $inspection)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <strong>{{ $inspection->vehicle->vehicleName ?? 'N/A' }}</strong><br>
                                <span class="badge bg-dark">{{ $inspection->vehicle->plateNo ?? 'N/A' }}</span>
                            </td>
                            <td>
                                <span class="badge {{ $inspection->inspectionType == 'pickup' ? 'bg-info' : 'bg-warning' }}">
                                    {{ strtoupper($inspection->inspectionType) }}
                                </span>
                            </td>
                            <td>
                                @php
                                    $cond = strtolower($inspection->carCondition);
                                    $color = ['excellent'=>'success', 'good'=>'primary', 'fair'=>'warning', 'poor'=>'danger'][$cond] ?? 'secondary';
                                @endphp
                                <span class="badge bg-{{ $color }}">{{ ucfirst($cond) }}</span>
                            </td>
                            <td class="text-center">
                                {!! $inspection->damageDetected 
                                    ? '<span class="text-danger"><i class="fas fa-exclamation-triangle"></i> Yes</span>' 
                                    : '<span class="text-success"><i class="fas fa-check-circle"></i> No</span>' !!}
                            </td>
                            <td>
                                {{ $inspection->staff->name ?? 'Staff ID: '.$inspection->staffID }}
                            </td>
                            <td>{{ $inspection->created_at->format('d M Y, h:i A') }}</td>
                            <td>
                                <a href="{{ route('admin.inspections.show', $inspection->inspectionID) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-search"></i> Review
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">No records found in the system.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $inspections->links() }} {{-- Pagination Laravel --}}
        </div>
    </div>
</div>
@endsection