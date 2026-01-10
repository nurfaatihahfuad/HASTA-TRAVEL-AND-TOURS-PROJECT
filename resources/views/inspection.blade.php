@extends('layouts.customer')

@section('content')
<style>
    /* CSS Variables - Scoped only to this page */
    :root {
        --reg-primary: #EC9A85;
        --reg-primary-dark: #D98B77;
        --reg-primary-light: #F9E0D9;
        --reg-primary-lightest: #FEF5F2;
    }

    body {
        background-image: url("/img/registration-bg.jpg");
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        background-repeat: no-repeat;
    }

    .reg-btn-primary {
        background-color: var(--reg-primary) !important;
        border-color: var(--reg-primary) !important;
        color: white !important;
    }
    .reg-btn-primary:hover {
        background-color: var(--reg-primary-dark) !important;
        border-color: var(--reg-primary-dark) !important;
    }
    .reg-btn-primary:focus {
        box-shadow: 0 0 0 4px rgba(236, 154, 133, 0.25) !important;
    }

    .reg-focus-ring:focus {
        border-color: var(--reg-primary) !important;
        box-shadow: 0 0 0 2px rgba(236, 154, 133, 0.25) !important;
        outline: none !important;
    }

    .required:after { content: " *"; color: #ef4444; }
</style>

<div class="container min-h-screen">
    <h2 class="reg-text-primary-dark">Car Inspection Checklist</h2>

    {{-- Alert success --}}
    @if(session('success'))
        <div class="alert alert-success reg-border-primary-light">
            {{ session('success') }}
        </div>
    @endif

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
            <label for="bookingID" class="required">Booking</label>
            <select name="bookingID" class="form-control reg-focus-ring" required>
                <option value="">-- Choose Booking --</option>
                @foreach($bookings as $b)
                    <option value="{{ $b->bookingID }}">
                        {{ $b->bookingID }} - Vehicle {{ $b->vehicleID }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label class="required">Car Condition</label>
            <input type="text" name="carCondition" class="form-control reg-focus-ring" required>
        </div>

        <div class="form-group">
            <label class="required">Mileage Returned</label>
            <input type="number" name="mileageReturned" class="form-control reg-focus-ring" required>
        </div>

        <div class="form-group">
            <label class="required">Fuel Level (%)</label>
            <input type="number" name="fuelLevel" class="form-control reg-focus-ring" required>
        </div>

        <div class="form-group">
            <label class="required">Damage Detected</label><br>
            <label><input type="radio" name="damageDetected" value="yes"> Yes</label>
            <label><input type="radio" name="damageDetected" value="no" checked> No</label>
        </div>

        <div class="form-group">
            <label>Remark</label>
            <textarea name="remark" class="form-control reg-focus-ring"></textarea>
        </div>

        <div class="form-group">
            <label>Evidence (image)</label>
            <input type="file" name="evidence" class="form-control reg-focus-ring">
        </div>

        <button type="submit" class="btn reg-btn-primary">Submit Inspection</button>
    </form>

    <hr>

    {{-- Senarai inspection --}}
    <h3 class="reg-text-primary-dark">Inspection Records</h3>
    <table class="table table-bordered reg-bg-primary-lightest">
        <thead class="reg-bg-primary-light">
            <tr>
                <th>ID</th>
                <th>Vehicle</th>
                <th>Condition</th>
                <th>Mileage</th>
                <th>Fuel</th>
                <th>Damage</th>
                <th>Staff</th>
            </tr>
        </thead>
        <tbody>
            @foreach($inspections as $insp)
                <tr>
                    <td>{{ $insp->inspectionID }}</td>
                    <td>{{ $insp->vehicleID }}</td>
                    <td>{{ $insp->carCondition }}</td>
                    <td>{{ $insp->mileageReturned }}</td>
                    <td>{{ $insp->fuelLevel }}%</td>
                    <td>{{ $insp->damageDetected ? 'Yes' : 'No' }}</td>
                    <td>{{ $insp->staffID }}</td>
                </tr>
            @endforeach
        </tbody>
        <td>
            <form action="{{ route('inspection.destroy', $inspection->inspectionID) }}" method="POST" onsubmit="return confirm('Confirm delete?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
            </form>
        </td>
    </table>
</div>
@endsection