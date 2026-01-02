@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 mb-4">
            <div class="section-card">
                <h5 class="mb-3">HASTA</h5>
                <nav class="d-grid gap-2">
                    <a class="sidebar-link" href="#">Dashboard</a>
                    <a class="sidebar-link" href="#">Customer Informations</a>
                    <a class="sidebar-link" href="#">Car Inspection Checklist</a>
                    <a class="sidebar-link" href="#">Report</a>
                    <a class="sidebar-link" href="#">Sales Record</a>
                    <a class="sidebar-link" href="#">Payment Record</a>
                    <a class="sidebar-link" href="#">Deposit Record</a>
                    <a class="sidebar-link" href="#">Bank Statement Record</a>
                    <hr>
                    <a class="sidebar-link" href="#">Profile</a>
                    <a class="sidebar-link" href="#">Settings</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="btn btn-outline-danger w-100 mt-2">Logout</button>
                    </form>
                </nav>
            </div>
        </div>

        <!-- Main -->
        <div class="col-md-9">
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
                        <h6 class="mb-3">Booking overview</h6>
                        <canvas id="adminBookingBar"></canvas>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="section-card">
                        <h6 class="mb-3">Car availability</h6>
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
                        <h6 class="mb-3">Car type</h6>
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
                        <h6 class="mb-3">Booking status</h6>
                        <canvas id="adminStatusPie"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Charts --}}
<script>
document.addEventListener('DOMContentLoaded', () => {
    // Bar chart
    const ctxBar = document.getElementById('adminBookingBar');
    new Chart(ctxBar, {
        type: 'bar',
        data: {
            labels: @json($weeklyLabels),
            datasets: [{
                label: 'Bookings',
                data: @json($weeklyData),
                backgroundColor: '#dc3545',
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

    // Pie chart
    const ctxPie = document.getElementById('adminStatusPie');
    new Chart(ctxPie, {
        type: 'pie',
        data: {
            labels: ['Cancelled', 'Booked', 'Pending'],
            datasets: [{
                data: [{{ $statusCancelled }}, {{ $statusBooked }}, {{ $statusPending }}],
                backgroundColor: ['#adb5bd', '#dc3545', '#212529']
            }]
        },
        options: { responsive: true }
    });
});
</script>
@endsection
