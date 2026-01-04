@extends('layouts.app')
@section('title', 'IT Admin Dashboard')

@section('content')

<!-- Welcome -->
<h3 class="mb-4">Welcome, {{ auth()->user()->name }}</h3>

<!-- Metrics -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="metric-card">
            <div class="metric-title">Total Users</div>
            <div class="metric-value">{{ number_format($totalUsers) }}</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="metric-card">
            <div class="metric-title">Total Staff</div>
            <div class="metric-value">{{ number_format($totalStaff) }}</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="metric-card">
            <div class="metric-title">Total Vehicles</div>
            <div class="metric-value">{{ number_format($totalVehicles) }}</div>
        </div>
    </div>
</div>

<!-- Charts -->
<div class="row g-3 mb-4">
    <div class="col-md-8">
        <div class="section-card">
            <h6 class="mb-3">System Overview</h6>
            <canvas id="itAdminBar"></canvas>
        </div>
    </div>
    <div class="col-md-4">
        <div class="section-card">
            <h6 class="mb-3">Staff Roles Distribution</h6>
            <canvas id="itAdminPie"></canvas>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    // Bar chart: system metrics
    const ctxBar = document.getElementById('itAdminBar');
    new Chart(ctxBar, {
        type: 'bar',
        data: {
            labels: ['Users','Staff','Vehicles'],
            datasets: [{
                label: 'System Metrics',
                data: [{{ $totalUsers }}, {{ $totalStaff }}, {{ $totalVehicles }}],
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

    // Pie chart: staff roles distribution (contoh statik)
    const ctxPie = document.getElementById('itAdminPie');
    new Chart(ctxPie, {
        type: 'pie',
        data: {
            labels: ['Salesperson','Runner','Other'],
            datasets: [{
                data: [40, 35, 25], // contoh statik, boleh diganti dengan data dari controller
                backgroundColor: ['#198754', '#dc3545', '#ffc107']
            }]
        },
        options: { responsive: true }
    });
});
</script>
@endpush
