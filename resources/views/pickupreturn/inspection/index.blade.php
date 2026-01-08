@extends('layouts.app')
@extends('layouts.runner')
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

    <a href="{{ route('inspection.create') }}" class="btn btn-outline-secondary px-4">+ New Inspection</a>

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
                        <form action="{{ route('inspection.destroy', $insp->inspectionID) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection