<!-- Runner Dashboard -->
@extends('layouts.runner')
@section('title', 'Runner Dashboard')

@section('content')

        <!-- Main -->
            <h3 class="mb-4">Welcome, {{ auth()->user()->name }}</h3>

            <!-- KPI cards -->
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="metric-card">
                        <div class="metric-title">Assigned today</div>
                        <div class="metric-value"></div>
                        <div class="metric-delta">↑ 4.1%</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="metric-card">
                        <div class="metric-title">Pending payments</div>
                        <div class="metric-value"></div>
                        <div class="metric-delta">↑ 2.5%</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="metric-card">
                        <div class="metric-title">Cancelled bookings</div>
                        <div class="metric-value">{{ $statusCancelled }}</div>
                        <div class="metric-delta">↓ 1.2%</div>
                    </div>
                </div>
            </div>

            <!-- Charts + Booking status -->
            <div class="row g-3 mb-4">
                <div class="col-md-8">
                    <div class="section-card">
                        <h6 class="mb-3">Weekly bookings</h6>
                        <canvas id="salesBookingBar"></canvas>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="section-card">
                        <h6 class="mb-3">Booking status</h6>
                        <canvas id="salesStatusPie"></canvas>
                    </div>
                </div>
            </div>

            <!-- All bookings -->
            <div class="section-card">
                <h6 class="mb-3">All bookings</h6>
                <div class="table-responsive">
                    <table class="table table-sm align-middle">
                        <thead>
                            <tr>
                                <th>Booking ID</th>
                                <th>Car</th>
                                <th>Status</th>
                                <th>Created At</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        
    </div>
</div>

{{-- Charts --}}
<script>
document.addEventListener('DOMContentLoaded', () => {
    const ctxBar = document.getElementById('salesBookingBar');
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
        options: { responsive: true, plugins: { legend: { display: false } } }
    });

    const ctxPie = document.getElementById('salesStatusPie');
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
@endsection

