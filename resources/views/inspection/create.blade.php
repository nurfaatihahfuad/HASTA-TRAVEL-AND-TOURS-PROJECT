@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Create Inspection</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('inspection.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label for="vehicleID" class="required">Vehicle</label>
            <input type="number" name="vehicleID" class="form-control" required>
        </div>

        <div class="form-group">
            <label class="required">Car Condition</label>
            <input type="text" name="carCondition" class="form-control" required>
        </div>

        <div class="form-group">
            <label class="required">Mileage Returned</label>
            <input type="number" name="mileageReturned" class="form-control" required>
        </div>

        <div class="form-group">
            <label class="required">Fuel Level (%)</label>
            <input type="number" name="fuelLevel" min="0" max="100" class="form-control" required>
        </div>

        <div class="form-group">
            <label class="required">Damage Detected</label><br>
            <label><input type="radio" name="damageDetected" value="1"> Yes</label>
            <label><input type="radio" name="damageDetected" value="0" checked> No</label>
        </div>

        <div class="form-group">
            <label>Remark</label>
            <textarea name="remark" class="form-control"></textarea>
        </div>

        <div class="form-group">
            <label>Fuel Evidence (required)</label>
            <input type="file" name="fuel_evidence" class="form-control" accept="image/*,.pdf" required>
        </div>

        <button type="submit" class="btn btn-primary">Submit Inspection</button>
    </form>
</div>
@endsection