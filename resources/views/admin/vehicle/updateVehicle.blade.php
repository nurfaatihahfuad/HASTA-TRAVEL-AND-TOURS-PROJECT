@extends('layouts.it_admin')

@section('title', 'Update Vehicle')

@section('content')
<div class="section-card">
    <h3><i class="fas fa-edit me-2"></i> Update Vehicle</h3>

    <form method="POST" action="{{ route('vehicles.update', $vehicle->vehicleID) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Vehicle Name</label>
            <input type="text" name="vehicleName" class="form-control" value="{{ $vehicle->vehicleName }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Plate Number</label>
            <input type="text" name="plateNo" class="form-control" value="{{ $vehicle->plateNo }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Year</label>
            <input type="number" name="year" class="form-control" value="{{ $vehicle->year }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Price Per Day (RM)</label>
            <input type="number" name="price_per_day" class="form-control" value="{{ $vehicle->price_per_day }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Price Per Hour (RM)</label>
            <input type="number" name="price_per_hour" class="form-control" value="{{ $vehicle->price_per_hour }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="3">{{ $vehicle->description }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Image</label>
            <input type="file" name="image" class="form-control" accept="image/*">
            @if($vehicle->image_url)
                <div class="mt-2">
                    <img src="{{ asset('img/'.$vehicle->image_url) }}" width="120" alt="Current Image">
                </div>
            @endif
        </div>

        <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select" required>
                <option value="available" @if($vehicle->available == 1) selected @endif>Available</option>
                <option value="unavailable" @if($vehicle->available == 0) selected @endif>Unavailable</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">
            <i class="fas fa-save me-1"></i> Update
        </button>
        <a href="{{ route('vehicles.index') }}" class="btn btn-secondary">
            <i class="fas fa-times me-1"></i> Cancel
        </a>
    </form>
</div>
@endsection
