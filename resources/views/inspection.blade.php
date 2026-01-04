@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Car Inspection Checklist</h2>

    <form action="{{ route('inspection.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- Vehicle Info -->
        <div class="mb-3">
            <label for="bookingID" class="form-label">Booking ID</label>
            <input type="text" name="bookingID" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="inspDate" class="form-label">Inspection Date</label>
            <input type="date" name="inspDate" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="carCondition" class="form-label">Car Condition</label>
            <textarea name="carCondition" class="form-control" required></textarea>
        </div>

        <div class="mb-3">
            <label for="mileageReturned" class="form-label">Mileage Returned</label>
            <input type="number" name="mileageReturned" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="fuelLevel" class="form-label">Fuel Level (%)</label>
            <input type="number" name="fuelLevel" class="form-control" required>
        </div>

        <!-- Damage Detected -->
        <div class="mb-3">
            <label class="form-label">Damage Detected</label><br>
            <input type="radio" name="damageDetected" value="yes"> Yes
            <input type="radio" name="damageDetected" value="no" checked> No
        </div>

        <div class="mb-3">
            <label for="remark" class="form-label">Remarks</label>
            <textarea name="remark" class="form-control"></textarea>
        </div>

        <div class="mb-3">
            <label for="evidence" class="form-label">Evidence Photo</label>
            <input type="file" name="evidence" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Submit Inspection</button>
    </form>
</div>
@endsection