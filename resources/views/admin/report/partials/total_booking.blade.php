<div class="section-card">
    <h5 class="mb-3">Total Booking Report</h5>

    @if(!empty($isPdf))
        <style>
            body {
                font-family: Arial, sans-serif;
                font-size: 12px;
                color: #333;
            }
            table {
                width: 100%;
                border-collapse: collapse;
                font-size: 12px;
            }
            th, td {
                border: 1px solid #000;
                padding: 6px;
                text-align: left;
            }
            th {
                background-color: #f2f2f2;
                font-weight: bold;
            }
        </style>
    @endif

    @if(empty($isPdf))
        <!-- Filter form -->
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

        <!-- Summary card - HANYA TOTAL SAHAJA -->
        <div class="row mb-4" id="summaryCards">
            <div class="col-md-12">
                <div class="card text-center bg-light">
                    <div class="card-body">
                        <h6 class="text-muted">Total Bookings</h6>
                        <p class="fs-4 mb-0 text-dark" id="totalCount">{{ $summary['total'] ?? 0 }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Export buttons - fixed at top -->
        <div class="mb-3 sticky-top bg-white p-3 shadow-sm" style="z-index: 1020;">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Export Reports</h5>
                <div>
                    <a href="{{ route('reports.total_booking.exportPdf', ['month' => request('month'), 'year' => request('year')]) }}" 
                    class="btn btn-danger me-2">
                        <i class="fas fa-file-pdf me-1"></i> Export to PDF
                    </a>
                    <a href="{{ route('reports.total_booking.exportExcel', ['month' => request('month'), 'year' => request('year')]) }}" 
                    class="btn btn-success">
                        <i class="fas fa-file-excel me-1"></i> Export to Excel
                    </a>
                </div>
            </div>
        </div>
    @endif

    <!-- Booking Table -->
    <div class="table-responsive">
        <table class="table table-hover" @if(!empty($isPdf)) style="border: 1px solid #000;" @endif>
            <thead class="table-light">
                <tr>
                    <th>Booking ID</th>
                    <th>User ID</th>
                    <th>Customer Name</th>
                    <th>Vehicle Name</th>
                    <th>Booking Period</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody id="reportTableBody">
                @forelse($data as $row)
                    <tr>
                        <td>{{ $row->bookingID }}</td>
                        <td>{{ $row->userID }}</td>
                        <td>{{ $row->name }}</td>
                        <td>{{ $row->vehicleName }}</td>
                        <td>
                            {{ \Carbon\Carbon::parse($row->pickup_dateTime)->format('d M Y H:i') }}
                            - {{ \Carbon\Carbon::parse($row->return_dateTime)->format('d M Y H:i') }}
                        </td>
                        <td>
                            @php
                                // Tukar "completed" kepada "successful" untuk paparan
                                $displayStatus = $row->bookingStatus === 'completed' ? 'successful' : $row->bookingStatus;
                            @endphp
                            <span class="badge bg-{{ $row->bookingStatus === 'completed' ? 'success' : ($row->bookingStatus === 'pending' ? 'warning' : 'secondary') }}">
                                {{ ucfirst($displayStatus) }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4">
                            <div class="text-muted">
                                <p class="mb-0">No bookings found for the selected filter</p>
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
document.addEventListener("DOMContentLoaded", function() {
    const form = document.getElementById('filterForm');
    const tbody = document.getElementById('reportTableBody');
    const totalCount = document.getElementById('totalCount');

    if (!form || !tbody) return;

    form.addEventListener('submit', function(e) {
        e.preventDefault();

        const params = new URLSearchParams(new FormData(form));

        fetch("{{ url('/admin/reports/total_booking/filter') }}?" + params, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(response => response.json())
        .then(result => {
            const data = result.data;
            const summary = result.summary;

            // Update table
            tbody.innerHTML = '';
            if (data.length === 0) {
                tbody.innerHTML = `<tr>
                    <td colspan="6" class="text-center py-4">
                        <div class="text-muted">
                            <p class="mb-0">No bookings found for the selected filter</p>
                        </div>
                    </td>
                </tr>`;
            } else {
                data.forEach(row => {
                    // Tukar "completed" kepada "successful" untuk paparan
                    const displayStatus = row.bookingStatus === 'completed' ? 'successful' : row.bookingStatus;
                    
                    tbody.innerHTML += `
                        <tr>
                            <td>${row.bookingID}</td>
                            <td>${row.userID}</td>
                            <td>${row.name}</td>
                            <td>${row.vehicleName}</td>
                            <td>${row.pickup_dateTime} - ${row.return_dateTime}</td>
                            <td>
                                <span class="badge bg-${row.bookingStatus === 'completed' ? 'success' : (row.bookingStatus === 'pending' ? 'warning' : 'secondary')}">
                                    ${displayStatus.charAt(0).toUpperCase() + displayStatus.slice(1)}
                                </span>
                            </td>
                        </tr>
                    `;
                });
            }

            // Update summary card - HANYA TOTAL
            totalCount.textContent = summary.total;
        })
        .catch(() => alert('Something went wrong. Please try again.'));
    });
});
</script>
@endif