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

    <!-- Report Content -->
    <div id="report-content">
        <p>Please select a category to view report.</p>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Simpan kategori semasa
    let currentCategory = null;

    document.getElementById('reportCategory').addEventListener('change', function() {
        currentCategory = this.value;
        fetch(`/admin/reports/${currentCategory}/ajax`)
            .then(response => response.text())
            .then(html => {
                document.getElementById('report-content').innerHTML = html;
            });
    });

    document.getElementById('filterBtn').addEventListener('click', function() {
        let month = document.getElementById('monthFilter').value;
        let year = document.getElementById('yearFilter').value;

        if (!currentCategory) {
            alert('Please select a category first');
            return;
        }

        let url = `/admin/reports/${currentCategory}/filter?month=${month}&year=${year}`;
        fetch(url)
            .then(response => response.text())
            .then(html => {
                document.getElementById('report-content').innerHTML = html;
            });
    });

</script>
@endpush



