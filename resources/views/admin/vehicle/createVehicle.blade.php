@extends('layouts.it_admin')

@section('title', 'Create Vehicle')

@section('content')
<div class="section-card">
    <h3><i class="fas fa-plus-circle me-2"></i> Add New Vehicle</h3>

    <form method="POST" action="{{ route('vehicles.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label class="form-label">Vehicle Name</label>
            <input type="text" name="vehicleName" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Plate Number</label>
            <input type="text" name="plateNo" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Year</label>
            <input type="number" name="year" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Price Per Day (RM)</label>
            <input type="number" name="price_per_day" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Price Per Hour (RM)</label>
            <input type="number" name="price_per_hour" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Image</label>
            <input type="file" name="image" class="form-control" accept="image/*">
        </div>

        <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select" required>
                <option value="available">Available</option>
                <option value="unavailable">Unavailable</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="3" placeholder="Enter vehicle description"></textarea>
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
