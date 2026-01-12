<div class="section-card">
    <h5 class="mb-3">Revenue Report</h5>


    <!-- Filter Form - SAMA SEPERTI DALAM JS -->
    @if(empty($isPdf))
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <select id="filterMonth" class="form-select">
                <option value="">All Months</option>
                <option value="1">January</option>
                <option value="2">February</option>
                <option value="3">March</option>
                <option value="4">April</option>
                <option value="5">May</option>
                <option value="6">June</option>
                <option value="7">July</option>
                <option value="8">August</option>
                <option value="9">September</option>
                <option value="10">October</option>
                <option value="11">November</option>
                <option value="12">December</option>
            </select>
        </div>
        <div class="col-md-3">
            <select id="filterYear" class="form-select">
                <option value="">All Years</option>
                @php
                    $currentYear = date('Y');
                    for($year = $currentYear; $year >= $currentYear - 5; $year--) {
                        echo "<option value='{$year}'>{$year}</option>";
                    }
                @endphp
            </select>
        </div>
        <div class="col-md-3">
            <button id="applyFilter" class="btn btn-primary w-100">
                <i class="fas fa-filter me-2"></i> Filter
            </button>
        </div>
        <div class="col-md-3">
            <button id="resetFilter" class="btn btn-outline-secondary w-100">
                <i class="fas fa-redo me-1"></i> Reset
            </button>
        </div>
    </div>
    @endif

    <!-- Export Buttons -->
    @if(empty($isPdf))
    <div class="mb-4 d-flex justify-content-between align-items-center">
        <div>
            <a href="/admin/reports/revenue/export-pdf" class="btn btn-danger me-2" id="pdfExportBtn">
                <i class="fas fa-file-pdf me-1"></i> Export PDF
            </a>
            <a href="/admin/reports/revenue/export-excel" class="btn btn-success" id="excelExportBtn">
                <i class="fas fa-file-excel me-1"></i> Export Excel
            </a>
        </div>
        <div class="text-muted">
            <small><i class="fas fa-info-circle me-1"></i> Export will use current filter</small>
        </div>
    </div>
    @endif

    @if(!empty($isPdf))
        <style>
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
            }
            .summary-box {
                margin-bottom: 15px;
                display: flex;
                justify-content: space-between;
            }
            .summary-box div {
                border: 1px solid #000;
                padding: 8px;
                flex: 1;
                text-align: center;
                margin-right: 5px;
                font-weight: bold;
            }
        </style>
    @endif

    @if(empty($isPdf))
        <!-- Debug Info (Temporary - boleh remove selepas test) -->
        <div class="alert alert-info mb-3" style="display: none;" id="debugInfo">
            <strong>Debug:</strong> 
            Data Count: <span id="dataCount">{{ count($data) }}</span> | 
            Chart Labels: <span id="chartLabels">{{ count($chart['labels'] ?? []) }}</span>
        </div>

        <!-- Summary Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h6>Total Sales</h6>
                        <p class="fs-5 mb-0 text-primary">RM {{ number_format($summary['total_sales'] ?? 0, 2) }}</p>
                        <small class="text-muted">All payments</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h6>Total Income</h6>
                        <p class="fs-5 mb-0 text-success">RM {{ number_format($summary['total_income'] ?? 0, 2) }}</p>
                        <small class="text-muted">Approved payments only</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h6>Average Duration</h6>
                        <p class="fs-5 mb-0 text-warning">{{ number_format($summary['avg_duration'] ?? 0, 1) }} hrs</p>
                        <small class="text-muted">Per booking</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h6>Approved Payments</h6>
                        <p class="fs-5 mb-0 text-info">{{ $summary['completed_payments'] ?? 0 }}</p>
                        <small class="text-muted">Successful bookings</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Error Message -->
        @if(isset($error))
        <div class="alert alert-danger mb-4">
            <strong>Error:</strong> {{ $error }}
        </div>
        @endif

        <!-- Chart Section -->
        @if(!empty($chart['labels']) && count($chart['labels']) > 0)
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">Revenue by Vehicle</h6>
                </div>
                <div class="card-body">
                    <canvas id="revenueChart" height="100"></canvas>
                </div>
            </div>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const ctxRevenue = document.getElementById('revenueChart').getContext('2d');
                    new Chart(ctxRevenue, {
                        type: 'bar',
                        data: {
                            labels: @json($chart['labels']),
                            datasets: [{
                                label: 'Revenue (RM)',
                                data: @json($chart['data']),
                                backgroundColor: '#dc3545',
                                borderColor: '#dc3545',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    display: false
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            return 'RM ' + context.parsed.y.toFixed(2);
                                        }
                                    }
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        callback: function(value) {
                                            return 'RM ' + value;
                                        }
                                    }
                                }
                            }
                        }
                    });
                });
            </script>
        @else
            <div class="alert alert-info mb-4">
                <i class="fas fa-chart-bar me-2"></i>
                No chart data available. There might be no completed bookings with payments yet.
            </div>
        @endif
    @else
        <!-- PDF Summary Boxes -->
        <div class="summary-box">
            <div>Total Sales: RM {{ number_format($summary['total_sales'] ?? 0, 2) }}</div>
            <div>Total Income: RM {{ number_format($summary['total_income'] ?? 0, 2) }}</div>
            <div>Avg Duration: {{ number_format($summary['avg_duration'] ?? 0, 1) }} hrs</div>
            <div>Approved Payments: {{ $summary['completed_payments'] ?? 0 }}</div>
        </div>
    @endif

    <!-- Revenue Table -->
    <div class="card mt-4">
        <div class="card-header">
            <h6 class="mb-0">Payment Details</h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" @if(!empty($isPdf)) style="border: 1px solid #000;" @endif>
                    <thead class="table-light">
                        <tr>
                            <th>Payment ID</th>
                            <th>Booking ID</th>
                            <th>Vehicle</th>
                            <th>Duration (hrs)</th>
                            <th>Payment Type</th>
                            <th class="text-end">Total Amount (RM)</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data as $row)
                            <tr>
                                <td><code>{{ $row->paymentID ?? 'N/A' }}</code></td>
                                <td>{{ $row->bookingID ?? 'N/A' }}</td>
                                <td>{{ $row->vehicleName ?? 'N/A' }}</td>
                                <td>{{ number_format($row->duration ?? 0, 1) }}</td>
                                <td>
                                    <span class="badge bg-secondary">
                                        {{ ucfirst($row->paymentType ?? 'N/A') }}
                                    </span>
                                </td>
                                <td class="text-end fw-bold">RM {{ number_format($row->totalAmount ?? 0, 2) }}</td>
                                <td>
                                    <span class="badge bg-{{ 
                                        ($row->paymentStatus == 'approved') ? 'success' : 
                                        (($row->paymentStatus == 'pending') ? 'warning' : 
                                        (($row->paymentStatus == 'rejected') ? 'danger' : 'secondary')) 
                                    }}">
                                        {{ ucfirst($row->paymentStatus ?? 'unknown') }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="fas fa-receipt fa-3x mb-3"></i>
                                        <h5 class="mb-2">No payment records found</h5>
                                        <p class="mb-0">
                                            There are no completed bookings with payments yet.<br>
                                            <small>Bookings must be marked as "successful" and have payment records.</small>
                                        </p>
                                        @if(isset($error))
                                        <div class="mt-3">
                                            <small class="text-danger">{{ $error }}</small>
                                        </div>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    @if(count($data) > 0)
                    <tfoot class="table-light">
                        <tr>
                            <td colspan="5" class="text-end fw-bold">Total:</td>
                            <td class="text-end fw-bold">RM {{ number_format($summary['total_sales'] ?? 0, 2) }}</td>
                            <td></td>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>
        </div>
    </div>

    <!-- Debug Script (Optional) -->
    @if(empty($isPdf) && config('app.debug'))
    <script>
        // Toggle debug info dengan Ctrl+D
        document.addEventListener('keydown', function(e) {
            if (e.ctrlKey && e.key === 'd') {
                e.preventDefault();
                const debugDiv = document.getElementById('debugInfo');
                debugDiv.style.display = debugDiv.style.display === 'none' ? 'block' : 'none';
            }
        });
    </script>
    @endif
</div>