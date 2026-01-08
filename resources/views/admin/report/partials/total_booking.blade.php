<div class="section-card">
    <h4><i class="fas fa-list-alt me-2"></i> Total Booking Report</h4>

    <!-- Charts side by side -->
    <div class="row mb-4">
        <div class="col-md-6">
            <h5><i class="fas fa-chart-pie me-2"></i> Booking Status Overview</h5>
            <canvas id="bookingPieChart" height="120"></canvas>
        </div>
        <div class="col-md-6">
            <h5><i class="fas fa-chart-bar me-2"></i> Booking Summary</h5>
            <canvas id="bookingBarChart" height="120"></canvas>
        </div>
    </div>

    <!-- Table -->
    <table id="bookingTable" class="table table-striped table-hover align-middle">
        <thead class="table-dark">
            <tr>
                <th>Booking ID</th>
                <th>Customer</th>
                <th>Vehicle</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Total Hours</th>
                <th>Total Price (RM)</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $booking)
            <tr>
                <td>{{ $booking->bookingID }}</td>
                <td>{{ $booking->user->name ?? 'N/A' }}</td>
                <td>{{ $booking->vehicle->vehicleName ?? 'N/A' }}</td>
                <td>{{ \Carbon\Carbon::parse($booking->pickup_dateTime)->format('d/m/Y H:i') }}</td>
                <td>{{ \Carbon\Carbon::parse($booking->return_dateTime)->format('d/m/Y H:i') }}</td>
                <td>{{ $booking->totalHours }}</td>
                <td>{{ number_format($booking->totalPrice, 2) }}</td>
                <td>
                    <span class="badge 
                        @if($booking->bookingStatus == 'completed') bg-success 
                        @elseif($booking->bookingStatus == 'pending') bg-warning 
                        @elseif($booking->bookingStatus == 'cancelled') bg-danger 
                        @else bg-secondary @endif">
                        {{ ucfirst($booking->bookingStatus) }}
                    </span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Export Buttons -->
    <div class="mt-3">
        <button class="btn btn-outline-danger" onclick="exportTableToPDF()">Export PDF</button>
        <button class="btn btn-outline-success" onclick="exportTableToExcel()">Export Excel</button>
    </div>
</div>

@push('scripts')
<script>

    // Pie Chart
    const pieCtx = document.getElementById('bookingPieChart').getContext('2d');
    new Chart(pieCtx, {
        type: 'pie',
        data: {
            labels: ['Completed', 'Pending', 'Cancelled'],
            datasets: [{
                data: [
                    {{ $summary['completed'] }},
                    {{ $summary['pending'] }},
                    {{ $summary['cancelled'] }}
                ],
                backgroundColor: ['#28a745','#ffc107','#dc3545']
            }]
        },
        options: {
            plugins: {
                legend: { position: 'bottom' },
                title: {
                    display: true,
                    text: 'Total Bookings: {{ $summary['total'] }}'
                }
            }
        }
    });

    // Bar Chart
    const barCtx = document.getElementById('bookingBarChart').getContext('2d');
    new Chart(barCtx, {
        type: 'bar',
        data: {
            labels: ['Total', 'Completed', 'Pending', 'Cancelled'],
            datasets: [{
                label: 'Bookings',
                data: [
                    {{ $summary['total'] }},
                    {{ $summary['completed'] }},
                    {{ $summary['pending'] }},
                    {{ $summary['cancelled'] }}
                ],
                backgroundColor: ['#0d6efd','#28a745','#ffc107','#dc3545']
            }]
        },
        options: {
            plugins: {
                title: {
                    display: true,
                    text: 'Booking Summary'
                }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
</script>
@endpush
