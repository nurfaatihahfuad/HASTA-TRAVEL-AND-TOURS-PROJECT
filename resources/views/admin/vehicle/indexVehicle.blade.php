@extends('layouts.vehicle')

@section('title', 'Vehicle Management')

@section('content')
<div class="section-card">
    <h3><i class="fas fa-car me-2"></i> Vehicle Management</h3>

    <a href="{{ route('vehicles.create') }}" class="btn btn-primary mb-3">
        <i class="fas fa-plus-circle"></i> Add New Vehicle
    </a>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>Vehicle ID</th>
                <th>Vehicle Name</th>
                <th>Type</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($vehicles as $vehicle)
            <tr>
                <td>{{ $vehicle->vehicleID }}</td>
                <td>{{ $vehicle->vehicleName }}</td>
                <td>{{ $vehicle->type }}</td>
                <td>{{ $vehicle->status }}</td>
                <td>
                    <a href="{{ route('vehicles.edit', $vehicle->vehicleID) }}" class="btn btn-sm btn-warning">
                        <i class="fas fa-edit"></i>
                    </a>
                    <form action="{{ route('vehicles.destroy', $vehicle->vehicleID) }}" method="POST" style="display:inline;">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
