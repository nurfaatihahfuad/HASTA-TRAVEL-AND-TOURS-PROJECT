@extends('layouts.it_admin')

@section('title','Reports')

@section('content')
<div class="section-card">
    <h3><i class="fas fa-chart-bar me-2"></i> Reports</h3>

    <!-- Category Dropdown -->
    <div class="mb-3">
        <label class="form-label fw-bold">Choose Report Category</label>
        <select class="form-select w-auto" id="reportCategory">
            <option disabled selected>Choose Category</option>
            <option value="revenue">Revenue Report</option>
            <option value="total_booking">Total Booking</option>
            <option value="top_college">Top College Booking Report</option>
            <option value="blacklisted">Blacklisted Customer</option>
        </select>
    </div>

    <!-- Filter Section -->
    <div class="mb-3 p-3 bg-light border rounded">
        <label class="form-label fw-bold">Filter by Month & Year</label>
        <div class="row g-2">
            <div class="col-md-3">
                <select id="monthFilter" class="form-select filter-select">
                    <option disabled selected>Choose Month</option>
                    @for($m=1; $m<=12; $m++)
                        <option value="{{ $m }}">{{ date('F', mktime(0,0,0,$m,1)) }}</option>
                    @endfor
                </select>
            </div>
            <div class="col-md-3">
                <select id="yearFilter" class="form-select filter-select">
                    <option disabled selected>Choose Year</option>
                    @for($y=date('Y'); $y>=2020; $y--)
                        <option value="{{ $y }}">{{ $y }}</option>
                    @endfor
                </select>
            </div>
            <div class="col-md-2">
                <button class="btn btn-danger w-100" id="filterBtn">Filter</button>
            </div>
        </div>
    </div>

    <!-- Report Content -->
    <div id="report-content">
        <p>Please select a category to view report.</p>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Dropdown category
    document.getElementById('reportCategory').addEventListener('change', function() {
        let category = this.value;
        fetch(`/admin/reports/${category}/ajax`)
            .then(response => response.text())
            .then(html => {
                document.getElementById('report-content').innerHTML = html;
            });
    });

    // Filter by month + year (Total Booking only)
    document.getElementById('filterBtn').addEventListener('click', function() {
        let month = document.getElementById('monthFilter').value;
        let year = document.getElementById('yearFilter').value;

        fetch(`/admin/reports/total_booking/filter?month=${month}&year=${year}`)
            .then(response => response.text())
            .then(html => {
                document.getElementById('report-content').innerHTML = html;
            });
    });
</script>
@endpush
