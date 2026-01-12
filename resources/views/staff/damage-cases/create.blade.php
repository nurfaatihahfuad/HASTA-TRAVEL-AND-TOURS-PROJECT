@extends('layouts.salesperson')

@section('content')
<div class="container-fluid">
    <div class="section-card">
        <h4>Create New Damage Case</h4>
        <hr>
        <form action="{{ route('staff.damage-cases.store') }}" method="POST">
            @csrf
            <label>Vehicle ID</label>
            <select name="vehicleID" id="vehicleSelect" class="form-control" required>
                <option value="">-- Select Vehicle --</option>
                @foreach($vehicles as $vehicle)
                    <option value="{{ $vehicle->vehicleID }}" data-plate="{{ $vehicle->plateNumber }}">
                        {{ $vehicle->brand }} ({{ $vehicle->vehicleID }})
                    </option>
                @endforeach
            </select>

            <div class="mt-3">
                <label>Plate Number</label>
                <input type="text" id="plateNumberDisplay" class="form-control" readonly>
            </div>

            <script>
                document.getElementById('vehicleSelect').addEventListener('change', function() {
                    const selectedOption = this.options[this.selectedIndex];
                    const plate = selectedOption.getAttribute('data-plate');
                    
                    // Masukkan ke dalam input
                    document.getElementById('plateNumberDisplay').value = plate ? plate : '';
                });
            </script>
            <div class="mb-3">
                <label>Customer</label>
                <select name="userID" class="form-control" required>
                    @foreach($customers as $customer)
                        <option value="{{ $customer->userID }}">{{ $customer->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label>Status</label>
                <select name="resolutionstatus" class="form-control">
                    <option value="Unresolved">Unresolved</option>
                    <option value="Resolved">Resolved</option>
                </select>
            </div>

            <button type="submit" class="btn btn-danger">Save Case</button>
        </form>
    </div>
</div>
@endsection