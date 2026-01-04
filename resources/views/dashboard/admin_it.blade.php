@extends('layouts.app')
@section('title', 'IT Admin Dashboard')

@section('content')
<div class="dashboard-container">
    <div class="row">
        <!-- Sidebar Section -->
        <div class="col-md-3">
            <div class="section-card">
                <h6 class="mb-3 fw-bold text-primary">Main Menu</h6>
                <ul class="list-unstyled">
                    <li><a href="{{ route('admin.it.dashboard') }}" class="sidebar-link">Dashboard</a></li>
                    <li><a href="{{ route('admin.it.users') }}" class="sidebar-link">Customer Informations</a></li>
                    <li><a href="{{ route('inspection.index') }}" class="sidebar-link">Car Inspection Checklist</a></li>
                    <li><a href="#" class="sidebar-link">Report</a></li>
                    <li><a href="{{ route('staff.index') }}" class="sidebar-link">Sales Record</a></li>
                    <li><a href="{{ route('payment.index') }}" class="sidebar-link">Payment Record</a></li>
                    <li><a href="#" class="sidebar-link">Deposit Record</a></li>
                    <li><a href="#" class="sidebar-link">Bank Statement Record</a></li>
                    <li><a href="{{ route('profile.edit') }}" class="sidebar-link">Profile</a></li>
                    <li><a href="#" class="sidebar-link">Settings</a></li>
                </ul>
            </div>
        </div>

        <!-- Main Content Section -->
        <div class="col-md-9">
            <!-- Welcome -->
            <h3 class="mb-4">Welcome, {{ auth()->user()->name }}</h3>

            <!-- Metrics -->
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="metric-card">
                        <div class="metric-title">New Booking</div>
                        <div class="metric-value">{{ number_format($newBookings) }}</div>
                        <div class="metric-delta">↑ 5.2%</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="metric-card">
                        <div class="metric-title">Rented Cars</div>
                        <div class="metric-value">{{ number_format($rentedCars) }}</div>
                        <div class="metric-delta">↑ 21.2%</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="metric-card">
                        <div class="metric-title">Available Cars</div>
                        <div class="metric-value">{{ number_format($availableCars) }}</div>
                        <div class="metric-delta">↑ 7.2%</div>
                    </div>
                </div>
            </div>

            <!-- Charts + Availability -->
            <div class="row g-3 mb-4">
                <div class="col-md-8">
                    <div class="section-card">
                        <h6 class="mb-3">Booking Overview (Weekly)</h6>
                        <canvas id="itAdminBookingBar"></canvas>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="section-card">
                        <h6 class="mb-3">Car Availability</h6>
                        <form class="row g-3">
                            <div class="col-12">
                                <label class="form-label">Car ID</label>
                                <select class="form-select">
                                    <option selected>Choose...</option>
                                    <option>AXIA-001</option>
                                    <option>MYVI-002</option>
                                </select>
                            </div>
                            <div class="col-6">
                                <label class="form-label">Date</label>
                                <input type="date" class="form-control">
                            </div>
                            <div class="col-6">
                                <label class="form-label">Time</label>
                                <input type="time" class="form-control">
                            </div>
                            <div class="col-12">
                                <button class="btn btn-primary w-100" type="button">CHECK</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Car Type + Booking Status -->
            <div class="row g-3">
                <div class="col-md-8">
                    <div class="section-card">
                        <h6 class="mb-3">Car Type</h6>
                        <div class="row g-3">
                            @foreach($carTypes as $type)
                                <div class="col-md-4">
                                    <div class="metric-card">
                                        <div class="metric-title">{{ $type['label'] }}</div>
                                        <div class="metric-value">{{ $type['value'] }}%</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="section-card">
                        <h6 class="mb-3">Booking Status</h6>
                        <canvas id="itAdminStatusPie"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    // Booking overview bar chart
    const ctxBar = document.getElementById('itAdminBookingBar');
    new Chart(ctxBar, {
        type: 'bar',
        data: {
            labels: @json($weeklyLabels),
            datasets: [{
                label: 'Bookings',
                data: @json($weeklyData),
                backgroundColor: '#0d6efd',
                borderRadius: 6
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 5 } }
            }
        }
    });

    // Booking status pie chart
    const ctxPie = document.getElementById('itAdminStatusPie');
    new Chart(ctxPie, {
        type: 'pie',
        data: {
            labels: ['Cancelled', 'Booked', 'Pending'],
            datasets: [{
                data: [{{ $statusCancelled }}, {{ $statusBooked }}, {{ $statusPending }}],
                backgroundColor: ['#adb5bd', '#0d6efd', '#ffc107']
            }]
        },
        options: { responsive: true }
    });
});
</script>
@endpush
