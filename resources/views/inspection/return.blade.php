@extends('layouts.salesperson')

@section('content')
<div class="container">
    <h2>Return Inspection for Booking #{{ $booking->bookingID }}</h2>

    <form action="{{ route('inspection.storeReturnInspection', $booking->bookingID) }}" 
          method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label>Car Condition</label>
            <input type="text" name="carCondition" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Mileage Returned</label>
            <input type="number" name="mileage" class="form-control" min="0" required>
        </div>

        <div class="form-group">
            <label>Fuel Level (%)</label>
            <input type="number" name="fuel_level" class="form-control" min="0" max="100" required>
        </div>

        <div class="form-group">
            <label>Fuel Evidence (required)</label>
            <input type="file" name="fuel_evidence" class="form-control" 
                   accept="image/*,.pdf" required>
        </div>

        <div class="form-group">
            <label>Damage Detected</label><br>
            <label><input type="radio" name="damageDetected" value="1" required> Yes</label>
            <label><input type="radio" name="damageDetected" value="0" required checked> No</label>
        </div>

        <div class="form-group">
            <label>Remark</label>
            <textarea name="notes" class="form-control"></textarea>
        </div>

        <h4>Upload Vehicle Photos</h4>
        <div class="form-group">
            <label>Front View</label>
            <input type="file" name="front_view" class="form-control" accept="image/*" required>
        </div>
        <div class="form-group">
            <label>Back View</label>
            <input type="file" name="back_view" class="form-control" accept="image/*" required>
        </div>
        <div class="form-group">
            <label>Right View</label>
            <input type="file" name="right_view" class="form-control" accept="image/*" required>
        </div>
        <div class="form-group">
            <label>Left View</label>
            <input type="file" name="left_view" class="form-control" accept="image/*" required>
        </div>

        <button type="submit" class="btn btn-success">Submit Return Inspection</button>
    </form>
</div>
@endsection