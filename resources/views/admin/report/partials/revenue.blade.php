<div class="section-card">
    <h5 class="mb-3">Revenue Report</h5>

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
        <!-- Summary Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h6>Total Sales</h6>
                        <p class="fs-5 mb-0 text-primary">RM {{ number_format($summary['total_sales'], 2) }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h6>Total Income</h6>
                        <p class="fs-5 mb-0 text-success">RM {{ number_format($summary['total_income'], 2) }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h6>Average Duration</h6>
                        <p class="fs-5 mb-0 text-warning">{{ number_format($summary['avg_duration'], 1) }} hrs</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h6>Completed Payments</h6>
                        <p class="fs-5 mb-0 text-info">{{ $summary['completed_payments'] }}</p>
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
            <canvas id="revenueChart" height="100" class="mb-4"></canvas>
            <script>
                const ctxRevenue = document.getElementById('revenueChart').getContext('2d');
                new Chart(ctxRevenue, {
                    type: 'bar',
                    data: {
                        labels: @json($chart['labels']),
                        datasets: [{
                            label: 'Revenue by Vehicle',
                            data: @json($chart['data']),
                            backgroundColor: '#dc3545'
                        }]
                    }
                });
            </script>
        @else
            <div class="alert alert-info mb-4">
                No chart data available
            </div>
        @endif
    @else
        <!-- PDF Summary Boxes -->
        <div class="summary-box">
            <div>Total Sales: RM {{ number_format($summary['total_sales'], 2) }}</div>
            <div>Total Income: RM {{ number_format($summary['total_income'], 2) }}</div>
            <div>Avg Duration: {{ number_format($summary['avg_duration'], 1) }} hrs</div>
            <div>Completed Payments: {{ $summary['completed_payments'] }}</div>
        </div>
    @endif

    <!-- Revenue Table -->
    <div class="table-responsive mt-4">
        <table class="table table-hover" @if(!empty($isPdf)) style="border: 1px solid #000;" @endif>
            <thead class="table-light">
                <tr>
                    <th>Payment ID</th>
                    <th>Booking ID</th>
                    <th>Vehicle</th>
                    <th>Duration (hrs)</th>
                    <th>Payment Type</th>
                    <th>Total Amount</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $row)
                    <tr>
                        <td><code>{{ $row->paymentID ?? 'N/A' }}</code></td>
                        <td>{{ $row->bookingID }}</td>
                        <td>{{ $row->vehicleName ?? 'N/A' }}</td>
                        <td>{{ $row->duration ?? 'N/A' }}</td>
                        <td>{{ $row->paymentType }}</td>
                        <td class="text-end">RM {{ number_format($row->totalAmount, 2) }}</td>
                        <td>
                            <span class="badge bg-{{ 
                                ($row->paymentStatus == 'approved') ? 'success' : 
                                (($row->paymentStatus == 'pending') ? 'warning' : 'danger') 
                            }}">
                                {{ ucfirst($row->paymentStatus) }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">
                            <div class="text-muted">
                                <i class="fas fa-receipt fa-2x mb-2"></i>
                                <p class="mb-0">No revenue data available</p>
                                @if(isset($error))
                                <small class="text-danger">{{ $error }}</small>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>