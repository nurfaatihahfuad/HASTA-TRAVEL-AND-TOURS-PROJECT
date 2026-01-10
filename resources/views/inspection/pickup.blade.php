@extends('layouts.customer')

@section('content')
<div class="container">
    <h2>Pickup Inspection for Booking #{{ $booking->bookingID }}</h2>

    <form action="{{ route('inspection.storePickupInspection', $booking->bookingID) }}" 
          method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group mb-3">
            <label>Car Condition (on Pickup)</label>
            <input type="text" name="carCondition" class="form-control" required>
        </div>

        <div class="form-group mb-3">
            <label>Initial Mileage</label>
            <input type="number" name="mileageReturned" class="form-control" required>
        </div>

        <div class="form-group mb-3">
            <label>Fuel Level (%)</label>
            <input type="number" name="fuelLevel" class="form-control" min="0" max="100" required>
        </div>

        <div class="form-group mb-3">
            <label>Fuel Evidence</label>
            <input type="file" name="fuel_evidence" class="form-control" accept="image/*" required>
        </div>

        <div class="form-group mb-3">
            <label>Any New Damage?</label><br>
            <label><input type="radio" name="damageDetected" value="1" required> Yes</label>
            <label><input type="radio" name="damageDetected" value="0" required checked> No</label>
        </div>

        <div class="form-group mb-3">
            <label>Remark / Notes</label>
            <textarea name="remark" class="form-control" required></textarea>
        </div>

        <h4>Upload Vehicle Photos (Return)</h4>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label>Front View</label>
                <input type="file" name="front_view" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
                <label>Back View</label>
                <input type="file" name="back_view" class="form-control" required>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label>Right Side View</label>
                <input type="file" name="right_view" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
                <label>Left Side View</label>
                <input type="file" name="left_view" class="form-control" required>
            </div>
        </div>

        <button type="submit" class="btn btn-danger">Submit Pickup Inspection</button>
    </form>
</div>
@endsection