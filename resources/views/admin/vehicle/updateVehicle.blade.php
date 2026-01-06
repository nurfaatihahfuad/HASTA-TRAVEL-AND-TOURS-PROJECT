@extends('layouts.it_admin')

@section('title', 'Update Vehicle')

@section('content')
<div class="section-card">
    <h3><i class="fas fa-edit me-2"></i> Update Vehicle</h3>

    <form method="POST" action="{{ route('vehicles.update', $vehicle->vehicleID) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Vehicle Name</label>
            <input type="text" name="vehicleName" class="form-control" value="{{ $vehicle->vehicleName }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Type</label>
            <input type="text" name="type" class="form-control" value="{{ $vehicle->type }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
                <option value="available" @if($vehicle->status == 'available') selected @endif>Available</option>
                <option value="unavailable" @if($vehicle->status == 'unavailable') selected @endif>Unavailable</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">Update</button>
        <a href="{{ route('vehicles.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
