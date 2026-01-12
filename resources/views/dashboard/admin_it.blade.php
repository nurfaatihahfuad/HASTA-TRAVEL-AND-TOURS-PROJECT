@extends('layouts.it_admin')
@section('title', 'IT Admin Dashboard')

@section('content')

<!-- Metrics -->
<h3 class="mb-4">Welcome, {{ auth()->user()->name }}</h3>
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="metric-card d-flex align-items-center justify-content-between">
            <div>
                <div class="metric-title">New Bookings (Today)</div>
                <div class="metric-value">{{ number_format($newBookings) }}</div>
            </div>
            <i class="fas fa-calendar-plus fa-2x text-primary"></i>
        </div>
    </div>
     <div class="col-md-4">
        <div class="metric-card d-flex align-items-center justify-content-between">
            <div>
                <div class="metric-title">Total Revenue</div>
                <div class="metric-value">RM {{ number_format($totalRevenue, 2) }}</div>
            </div>
            <i class="fas fa-wallet fa-2x text-success"></i>
        </div>
    </div>

    <div class="col-md-4">
        <div class="metric-card d-flex align-items-center justify-content-between">
            <div>
                <div class="metric-title">Available Vehicles</div>
                <div class="metric-value">{{ $availableCars }}</div>
            </div>
            <i class="fas fa-car fa-2x text-info"></i>
        </div>
    </div>
</div>

<!-- Charts + Availability -->
<div class="row g-3 mb-4">
    <div class="col-md-8">
        <div class="section-card">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <div>
                <h6 class="mb-0">Booking Overview</h6>
                <small class="text-muted">Bookings created per week</small>
            </div>
            <span class="badge bg-light text-dark">Last 7 days</span>
        </div>
        <canvas id="adminBookingBar" height="120"></canvas>
    </div>

    </div>
    <div class="col-md-4">
        <div class="section-card">
    <h6 class="mb-2">Vehicle Availability Check</h6>
    <small class="text-muted d-block mb-3">
        Check if a vehicle is available at a specific date & time
    </small>
    @if(session('success'))
        <div class="alert alert-success mt-3">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger mt-3">
            {{ session('error') }}
        </div>
    @endif
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif


    <form method="POST" action="{{ route('admin.checkAvailability') }}" class="row g-3">
        @csrf

        <div class="col-12">
            <label class="form-label">Vehicle</label>
            <select class="form-select" name="vehicleID" required>
                <option selected disabled>Select vehicle</option>
                @foreach($vehicles as $vehicle)
                    <option value="{{ $vehicle->vehicleID }}">
                        {{ $vehicle->vehicleName }} ({{ $vehicle->plateNo }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-6">
            <label class="form-label">Pickup Date & Time</label>
            <input type="datetime-local" name="pickup_dateTime" class="form-control" required>
        </div>

        <div class="col-6">
            <label class="form-label">Return Date & Time</label>
            <input type="datetime-local" name="return_dateTime" class="form-control" required>
        </div>

        <div class="col-12">
            <button class="btn btn-primary w-100">
                <i class="fas fa-search me-1"></i> Check Availability
            </button>
        </div>
    </form>

</div>

    </div>
</div>

<!-- Booking Status + Commission Overview -->
<div class="row g-3 mb-4">

    <!-- Booking Status Pie -->
    <div class="col-md-4">
        <div class="section-card h-100">
            <h6 class="mb-3">Booking Status</h6>
            <canvas id="adminStatusPie"></canvas>
        </div>
    </div>

    <!-- Commission Overview -->
    <div class="col-md-8">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Staff Commission Overview</h5>

                <a href="{{ route('admin.commissionVerify.index') }}" class="btn btn-sm btn-primary">
                    View More
                </a>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Staff Name</th>
                                <th>Commission Type</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($commissionOverview as $commission)
                                <tr>
                                    <td>{{ $commission->user->name ?? 'N/A' }}</td>
                                    <td>{{ ucfirst($commission->commissionType) }}</td>
                                    <td>
                                        <span class="badge
                                            @if($commission->status == 'approved') bg-success
                                            @elseif($commission->status == 'pending') bg-warning text-dark
                                            @else bg-danger
                                            @endif
                                        ">
                                            {{ ucfirst($commission->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center">
                                        No commission records found
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>


</div>
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {

    // Booking Overview (Bar)
    new Chart(document.getElementById('adminBookingBar'), {
        type: 'bar',
        data: {
            labels: @json($weeklyLabels),
            datasets: [{
                data: @json($weeklyData),
                backgroundColor: '#0d6efd',
                borderRadius: 6
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    // Booking Status (Pie)
    new Chart(document.getElementById('adminStatusPie'), {
        type: 'pie',
        data: {
            labels: ['Booked', 'Pending', 'Rejected'],
            datasets: [{
                data: [
                    {{ $statusBooked }},
                    {{ $statusPending }},
                    {{ $statusRejected }}
                ],
                backgroundColor: ['#28a745', '#ffc107', '#dc3545']
            }]
        },
        options: { responsive: true }
    });

});
</script>
@endpush

@endsection