@extends('layouts.it_admin')

@section('title', 'Vehicle Management')

@section('content')
<!-- Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1"><i class="fas fa-car me-2"></i> Vehicle Management</h4>
        <p class="text-muted mb-0">Manage all registered vehicles in the system</p>
    </div>
    <a href="{{ route('vehicles.create') }}" class="btn btn-primary">
        <i class="fas fa-plus-circle me-2"></i> Add New Vehicle
    </a>
</div>

<!-- Stats Card -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card border-0 bg-primary bg-opacity-10">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="bg-primary bg-opacity-25 rounded-circle p-3 me-3">
                        <i class="fas fa-car text-primary"></i>
                    </div>
                    <div>
                        <h5 class="card-title mb-0">{{ $vehicles->count() }}</h5>
                        <p class="text-muted mb-0">Total Vehicles</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-3">
        <div class="card border-0 bg-success bg-opacity-10">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="bg-success bg-opacity-25 rounded-circle p-3 me-3">
                        <i class="fas fa-check-circle text-success"></i>
                    </div>
                    <div>
                        <h5 class="card-title mb-0">{{ $vehicles->where('available', 1)->count() }}</h5>
                        <p class="text-muted mb-0">Available</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-3">
        <div class="card border-0 bg-secondary bg-opacity-10">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="bg-secondary bg-opacity-25 rounded-circle p-3 me-3">
                        <i class="fas fa-times-circle text-secondary"></i>
                    </div>
                    <div>
                        <h5 class="card-title mb-0">{{ $vehicles->where('available', 0)->count() }}</h5>
                        <p class="text-muted mb-0">Unavailable</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filter Buttons -->
<div class="d-flex justify-content-center mb-4">
    <div class="btn-group" role="group">
        <a href="{{ route('vehicles.index') }}" 
           class="btn {{ !request('filter') ? 'btn-primary' : 'btn-outline-primary' }}">
            All Vehicles
        </a>
        <a href="{{ route('vehicles.index', ['filter' => 'available']) }}" 
           class="btn {{ request('filter') == 'available' ? 'btn-primary' : 'btn-outline-primary' }}">
            Available
        </a>
        <a href="{{ route('vehicles.index', ['filter' => 'unavailable']) }}" 
           class="btn {{ request('filter') == 'unavailable' ? 'btn-primary' : 'btn-outline-primary' }}">
            Unavailable
        </a>
    </div>
</div>

<!-- Vehicle Table -->
<div class="section-card">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead class="table-light">
                <tr>
                    <th>Vehicle ID</th>
                    <th>Name</th>
                    <th>Plate No</th>
                    <th>Year</th>
                    <th>Price/Day</th>
                    <th>Price/Hour</th>
                    <th>Status</th>
                    <th>Description</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($vehicles as $vehicle)
                    <tr>
                        <td><span class="fw-bold text-primary">{{ $vehicle->vehicleID }}</span></td>
                        <td>{{ $vehicle->vehicleName }}</td>
                        <td>{{ $vehicle->plateNo }}</td>
                        <td>{{ $vehicle->year }}</td>
                        <td><span class="badge bg-info text-dark">RM {{ $vehicle->price_per_day }}</span></td>
                        <td><span class="badge bg-info text-dark">RM {{ $vehicle->price_per_hour }}</span></td>
                        <td>
                            <span class="badge bg-{{ $vehicle->available ? 'success' : 'secondary' }}">
                                {{ $vehicle->available ? 'Available' : 'Unavailable' }}
                            </span>
                        </td>
                        <td style="max-width: 250px;" class="text-truncate">{{ $vehicle->description }}</td>
                        <td>
                            @if($vehicle->image_url)
                                <img src="{{ asset('img/'.$vehicle->image_url) }}" width="80" class="rounded shadow-sm">
                            @else
                                <span class="text-muted">No image</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('vehicles.edit', $vehicle->vehicleID) }}" 
                                   class="btn btn-outline-primary" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="text-center py-4">
                            <div class="text-muted">
                                <i class="fas fa-car fa-2x mb-3"></i>
                                <p class="mb-0">No vehicles found</p>
                                <a href="{{ route('vehicles.create') }}" class="btn btn-primary mt-3">
                                    <i class="fas fa-plus-circle me-2"></i> Add First Vehicle
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
