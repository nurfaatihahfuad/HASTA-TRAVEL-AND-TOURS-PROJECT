@extends('layouts.app')
@section('title', 'Finance Admin Dashboard')

@section('content')

<!-- Welcome -->
<h3 class="mb-4">Welcome, {{ auth()->user()->name }}</h3>

<!-- Metrics -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="metric-card">
            <div class="metric-title">Total Payments</div>
            <div class="metric-value">{{ number_format($totalPayments) }}</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="metric-card">
            <div class="metric-title">Pending Payments</div>
            <div class="metric-value">{{ number_format($pendingPayments) }}</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="metric-card">
            <div class="metric-title">Completed Payments</div>
            <div class="metric-value">{{ number_format($completedPayments) }}</div>
        </div>
    </div>
</div>

<!-- Revenue -->
<div class="row g-3 mb-4">
    <div class="col-md-12">
        <div class="metric-card">
            <div class="metric-title">Total Revenue</div>
            <div class="metric-value">RM {{ number_format($totalRevenue, 2) }}</div>
        </div>
    </div>
</div>

<!-- Charts -->
<div class="row g-3 mb-4">
    <div class="col-md-8">
        <div class="section-card">
            <h6 class="mb-3">Payment Overview</h6>
            <canvas id="financeBar"></canvas>
        </div>
    </div>
    <div class="col-md-4">
        <div class="section-card">
            <h6 class="mb-3">Payment Status</h6>
            <canvas id="financePie"></canvas>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    // Bar chart: payment metrics
    const ctxBar = document.getElementById('financeBar');
    new Chart(ctxBar, {
        type: 'bar',
        data: {
            labels: ['Total','Pending','Completed'],
            datasets: [{
                label: 'Payments',
                data: [{{ $totalPayments }}, {{ $pendingPayments }}, {{ $completedPayments }}],
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

    // Pie chart: payment status
    const ctxPie = document.getElementById('financePie');
    new Chart(ctxPie, {
        type: 'pie',
        data: {
            labels: ['Pending','Completed'],
            datasets: [{
                data: [{{ $pendingPayments }}, {{ $completedPayments }}],
                backgroundColor: ['#ffc107', '#198754']
            }]
        },
        options: { responsive: true }
    });
});
</script>
@endpush
