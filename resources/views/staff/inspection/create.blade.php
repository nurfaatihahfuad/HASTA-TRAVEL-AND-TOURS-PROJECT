
@extends('layouts.runner')
@section('title', 'Add New Inspection')
@section('content')
<div class="container min-h-screen">
    <h2 class="reg-text-primary-dark">Create Car Inspection</h2>

    {{-- Alert error --}}
    @if($errors->any())
        <div class="alert alert-danger reg-border-primary-light">
            <ul class="mb-0">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form create inspection --}}
    <form action="{{ route('inspection.store') }}" method="POST" enctype="multipart/form-data" class="p-3 reg-bg-primary-light rounded">
        @csrf

        <div class="form-group">
            <label for="vehicleID" class="required">Vehicle</label>
            <select name="vehicleID" class="form-control reg-focus-ring" required>
                <option value="">-- Choose Vehicle --</option>
                @foreach($bookings as $b)
                    <option value="{{ $b->vehicleID }}">
                         {{ $b->vehicle->vehicleName }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label class="required">Car Condition</label>
            <input type="text" name="carCondition" class="form-control reg-focus-ring" required>
        </div>

        <div class="form-group">
            <label class="required">Mileage Returned (km) </label>
            <input type="number" name="mileageReturned" class="form-control reg-focus-ring" required>
        </div>

        <div class="form-group">
            <label class="required">Fuel Level (%)</label>
            <input type="number" name="fuelLevel" class="form-control reg-focus-ring" required>
        </div>

        <div class="form-group">
            <label class="required">Damage Detected</label><br>
            <label><input type="radio" name="damageDetected" value="1"> Yes</label>
            <label><input type="radio" name="damageDetected" value="0" checked> No</label>
        </div>

        <div class="form-group">
            <label>Remark</label>
            <textarea name="remark" class="form-control reg-focus-ring"></textarea>
        </div>

        <div class="form-group">
            <label>Evidence (image)</label>
            <input type="file" name="evidence" class="form-control reg-focus-ring">
        </div>

        <button type="submit" class="btn btn-outline-secondary px-4">Submit Inspection</button>
    </form>
</div>
@endsection