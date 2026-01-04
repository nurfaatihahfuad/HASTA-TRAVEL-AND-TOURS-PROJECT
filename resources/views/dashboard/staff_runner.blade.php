@extends('layouts.app')
@section('title', 'Runner Dashboard')

@section('content')
<div class="container py-4">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 mb-4">
            <div class="section-card">
                <h5 class="mb-3">HASTA</h5>
                <nav class="d-grid gap-2">
                    <a class="sidebar-link" href="#">Dashboard</a>
                    <a class="sidebar-link" href="#">Car Inspection Checklist</a>
                    <a class="sidebar-link" href="#">Blacklisted Record</a>
                    <a class="sidebar-link" href="#">Sales Record</a>
                    <a class="sidebar-link" href="#">Payment Record</a>
                    <a class="sidebar-link" href="#">Pending Payment</a>
                    <a class="sidebar-link" href="#">Damage Case</a>
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
            <!-- KPI cards -->
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="metric-card">
                        <div class="metric-title">Assigned today</div>
                        <div class="metric-value">{{ $assignedToday }}</div>
                        <div class="metric-delta">↑ 4.1%</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="metric-card">
                        <div class="metric-title">Pending payments</div>
                        <div class="metric-value">{{ $pendingPayments ?? 0 }}</div>
                        <div class="metric-delta">↑ 2.5%</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="metric-card">
                        <div class="metric-title">Damage cases</div>
                        <div class="metric-value">{{ $damageCases }}</div>
                        <div class="metric-delta">↓ 1.2%</div>
                    </div>
                </div>
            </div>

            <!-- Charts + Assigned list -->
            <div class="row g-3 mb-4">
                <div class="col-md-8">
                    <div class="section-card">
                        <h6 class="mb-3">Weekly productivity</h6>
                        <canvas id="runnerBookingBar"></canvas>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="section-card">
                        <h6 class="mb-3">Booking status</h6>
                        <canvas id="runnerStatusPie"></canvas>
                    </div>
                </div>
            </div>

            <!-- Assigned bookings -->
            <div class="section-card">
                <h6 class="mb-3">Assigned bookings</h6>
                <div class="table-responsive">
                    <table class="table table-sm align-middle">
                        <thead>
                            <tr>
                                <th>Booking ID</th>
                                <th>Car</th>
                                <th>Status</th>
                                <th>Start</th>
                                <th>End</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bookings as $b)
                                <tr>
                                    <td>{{ $b->id }}</td>
                                    <td>{{ $b->carModel ?? '—' }}</td>
                                    <td><span class="badge bg-secondary">{{ $b->bookingStatus }}</span></td>
                                    <td>{{ $b->start_date ?? '—' }}</td>
                                    <td>{{ $b->end_date ?? '—' }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="5">No bookings assigned.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Charts --}}
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const ctxBar = document.getElementById('runnerBookingBar');
    new Chart(ctxBar, {
        type: 'bar',
        data: {
            labels: @json($weeklyLabels),
            datasets: [{
                label: 'Tasks',
                data: @json($weeklyData),
                backgroundColor: '#dc3545',
                borderRadius: 6
            }]
        },
        options: { responsive: true, plugins: { legend: { display: false } } }
    });

    const ctxPie = document.getElementById('runnerStatusPie');
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
@endpush
@endsection
