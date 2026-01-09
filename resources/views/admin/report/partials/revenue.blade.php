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

        <!-- Chart -->
        <canvas id="revenueChart" height="100"></canvas>

        <script>
            const ctxRevenue = document.getElementById('revenueChart').getContext('2d');
            new Chart(ctxRevenue, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($chart['labels']) !!},
                    datasets: [{
                        label: 'Revenue by Vehicle',
                        data: {!! json_encode($chart['data']) !!},
                        backgroundColor: '#dc3545'
                    }]
                }
            });
        </script>
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
    <div class="table-responsive">
        <table class="table table-hover" @if(!empty($isPdf)) style="border: 1px solid #000;" @endif>
            <thead class="table-light">
                <tr>
                    <th>Vehicle</th>
                    <th>Booking ID</th>
                    <th>Duration (hrs)</th>
                    <th>Payment Type</th>
                    <th>Total Amount</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $row)
                    <tr>
                        <td>{{ $row->vehicleName }}</td>
                        <td>{{ $row->bookingID }}</td>
                        <td>{{ $row->duration }}</td>
                        <td>{{ $row->paymentType }}</td>
                        <td>RM {{ number_format($row->totalAmount, 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-4">
                            <div class="text-muted">
                                <p class="mb-0">No revenue data available</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
