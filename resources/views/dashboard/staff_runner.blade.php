@extends('layouts.runner')
@section('title', 'Runner Dashboard')

@section('content')

<h3 class="mb-4">Welcome, {{ auth()->user()->name }}</h3>

<!-- KPI cards -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="metric-card">
            <div class="metric-title">Total Inspections</div>
            <div class="metric-value">{{ $totalInspections }}</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="metric-card">
            <div class="metric-title">Inspections Today</div>
            <div class="metric-value">{{ $inspectionToday }}</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="metric-card">
            <div class="metric-title">Cars OK</div>
            <div class="metric-value">{{ $okCount }}</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="metric-card">
            <div class="metric-title">Damaged Cars</div>
            <div class="metric-value">{{ $damagedCount }}</div>
        </div>
    </div>
</div>

<!-- Charts -->
<div class="row g-3 mb-4">
    <div class="col-md-8">
        <div class="section-card">
            <h6 class="mb-3">Weekly Inspections</h6>
            <canvas id="inspectionBar"></canvas>
        </div>
    </div>

    <div class="col-md-4">
        <div class="section-card">
            <h6 class="mb-3">Inspection Status</h6>
            <canvas id="inspectionPie"></canvas>
        </div>
    </div>
</div>

<!-- All inspections table -->
<div class="section-card">
    <h6 class="mb-3">All Inspections</h6>
    <div class="table-responsive">
        <table class="table table-sm align-middle">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Car</th>
                    <th>Condition</th>
                    <th>Damage</th>
                    <th>Inspection Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($inspections as $inspection)
                    <tr>
                        <td>{{ $inspection->inspectionID }}</td>
                        <td>{{ $inspection->vehicle->plate_no ?? '-' }}</td>
                        <td>{{ $inspection->carCondition }}</td>
                        <td>
                            @if($inspection->damageDetected)
                                <span class="badge bg-danger">Damaged</span>
                            @else
                                <span class="badge bg-success">OK</span>
                            @endif
                        </td>
                        <td>{{ optional($inspection->created_at)->format('d/m/Y') ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">No inspections found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Charts JS --}}
<script>
document.addEventListener('DOMContentLoaded', () => {

    // Bar Chart - Weekly Inspections
    new Chart(document.getElementById('inspectionBar'), {
        type: 'bar',
        data: {
            labels: @json($weeklyLabels),
            datasets: [{
                label: 'Inspections',
                data: @json($weeklyData),
                backgroundColor: '#0d6efd',
                borderRadius: 6
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } }
        }
    });

    // Pie Chart - OK vs Damaged
    new Chart(document.getElementById('inspectionPie'), {
        type: 'pie',
        data: {
            labels: ['OK', 'Damaged'],
            datasets: [{
                data: [{{ $okCount }}, {{ $damagedCount }}],
                backgroundColor: ['#198754', '#dc3545']
            }]
        },
        options: { responsive: true }
    });

});
</script>

@endsection
