@extends('layouts.salesperson')
@section('title', 'Inspection Management')
@section('content')
<div class="container min-h-screen">
    <h2 class="reg-text-primary-dark">Inspection Records</h2>

    {{-- Alert success --}}
    @if(session('success'))
        <div class="alert alert-success reg-border-primary-light">
            {{ session('success') }}
        </div>
    @endif

    <table class="table table-bordered reg-bg-primary-lightest">
        <thead class="reg-bg-primary-light">
            <tr>
                <th>ID</th>
                <th>Vehicle</th>
                <th>Condition</th>
                <th>Mileage (km) </th>
                <th>Fuel (%) </th>
                <th>Damage</th>
                <th>Staff</th>
                <th>Action</th>
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
                    <td>
                        <a href="{{ route('inspection.edit', $insp->inspectionID) }}" class="btn btn-sm btn-warning">Edit</a>
                       
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection