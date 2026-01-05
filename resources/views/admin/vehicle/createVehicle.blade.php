@extends('layouts.vehicle')

@section('title', 'Create Vehicle')

@section('content')
<div class="section-card">
    <h3><i class="fas fa-plus-circle me-2"></i> Add New Vehicle</h3>

    <form method="POST" action="{{ route('vehicles.store') }}">
        @csrf

        <div class="mb-3">
            <label class="form-label">Vehicle Name</label>
            <input type="text" name="vehicleName" class="form-control" placeholder="Enter vehicle name">
        </div>

        <div class="mb-3">
            <label class="form-label">Type</label>
            <input type="text" name="type" class="form-control" placeholder="Enter vehicle type">
        </div>

        <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
                <option value="available">Available</option>
                <option value="unavailable">Unavailable</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save me-1"></i> Save
        </button>
        <a href="{{ route('vehicles.index') }}" class="btn btn-secondary">
            <i class="fas fa-times me-1"></i> Cancel
        </a>
    </form>
</div>
@endsection
