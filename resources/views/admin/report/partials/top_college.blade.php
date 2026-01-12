{{-- admin/report/partials/top_college.blade.php --}}
<div class="section-card">
    <h5 class="mb-3">Top College Booking Report</h5>

    @if(!empty($isPdf))
        <style>
            table { width: 100%; border-collapse: collapse; font-size: 12px; }
            th, td { border: 1px solid #000; padding: 6px; text-align: left; }
            th { background-color: #f2f2f2; }
        </style>
    @endif

    @if(empty($isPdf))
        <form id="filterForm" class="row g-3 mb-4">
            <div class="col-md-3">
                <select name="month" class="form-select">
                    <option value="">Select Month</option>
                    @for($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->month($m)->format('F') }}
                        </option>
                    @endfor
                </select>
            </div>
            <div class="col-md-3">
                <select name="year" class="form-select">
                    <option value="">Select Year</option>
                    @for($y = now()->year; $y >= now()->year - 5; $y--)
                        <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>
                            {{ $y }}
                        </option>
                    @endfor
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-filter me-2"></i> Filter
                </button>
            </div>
        </form>

        <div class="mb-3">
            <a href="{{ route('reports.top_college.exportPdf', ['month' => request('month'), 'year' => request('year')]) }}" 
               class="btn btn-danger me-2">
                <i class="fas fa-file-pdf me-1"></i> Export to PDF
            </a>
            <a href="{{ route('reports.top_college.exportExcel', ['month' => request('month'), 'year' => request('year')]) }}" 
               class="btn btn-success">
                <i class="fas fa-file-excel me-1"></i> Export to Excel
            </a>
        </div>
    @endif

    <div class="table-responsive">
        <table class="table table-hover" @if(!empty($isPdf)) style="border: 1px solid #000;" @endif>
            <thead class="table-light">
                <tr>
                    <th>Booking ID</th>
                    <th>User ID</th>
                    <th>Name</th>
                    <th>College</th>
                    <th>Vehicle</th>
                    <th>Booking Period</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody id="reportTableBody">
            @forelse($data ?? [] as $row)
                <tr>
                    <td>{{ $row->bookingID ?? 'N/A' }}</td>
                    <td>{{ $row->userID ?? 'N/A' }}</td>
                    <td>{{ $row->name ?? 'N/A' }}</td>
                    <td>{{ $row->collegeName ?? 'N/A' }}</td>
                    <td>{{ $row->vehicleName ?? 'N/A' }}</td>
                    <td>
                        @if(isset($row->pickup_dateTime) && isset($row->return_dateTime))
                            {{ \Carbon\Carbon::parse($row->pickup_dateTime)->format('d M Y H:i') }}
                            - {{ \Carbon\Carbon::parse($row->return_dateTime)->format('d M Y H:i') }}
                        @else
                            N/A
                        @endif
                    </td>
                    <td>
                        <span class="badge bg-{{ 
                            ($row->bookingStatus ?? '') === 'successful' ? 'success' : 
                            (($row->bookingStatus ?? '') === 'pending' ? 'warning' : 
                            (($row->bookingStatus ?? '') === 'rejected' ? 'danger' : 'secondary')) 
                        }}">
                            {{ isset($row->bookingStatus) ? ucfirst($row->bookingStatus) : 'Unknown' }}
                        </span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center py-4">
                        <div class="text-muted">
                            <i class="fas fa-info-circle fa-2x mb-2"></i>
                            <p class="mb-0">No college booking data found</p>
                            <small class="text-muted">
                                @if(isset($error))
                                    {{ $error }}
                                @else
                                    There might be no bookings from students with college information yet.
                                @endif
                            </small>
                        </div>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

@if(empty($isPdf))
<script>
// JavaScript untuk filter form (ini untuk non-AJAX view)
document.addEventListener("DOMContentLoaded", function() {
    const form = document.getElementById('filterForm');
    const tbody = document.getElementById('reportTableBody');

    if (!form || !tbody) return;

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const params = new URLSearchParams(new FormData(form));
        window.location.href = "{{ url()->current() }}?" + params;
    });
});
</script>
@endif