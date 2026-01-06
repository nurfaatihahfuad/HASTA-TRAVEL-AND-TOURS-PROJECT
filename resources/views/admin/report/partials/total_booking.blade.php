<div class="section-card">
    <h4><i class="fas fa-list-alt me-2"></i> Total Booking Report</h4>

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

    <!-- Chart -->
    <canvas id="bookingChart" height="100"></canvas>

    <!-- Export Buttons -->
    <div class="mt-3">
        <button class="btn btn-outline-danger" onclick="exportTableToPDF()">Export PDF</button>
        <button class="btn btn-outline-success" onclick="exportTableToExcel()">Export Excel</button>
    </div>
</div>

@push('scripts')
<script>
    // Chart.js untuk summary
    const ctx = document.getElementById('bookingChart').getContext('2d');
    new Chart(ctx, {
        type: 'pie',
        data: {
            labels: ['Total', 'Completed', 'Pending', 'Cancelled'],
            datasets: [{
                data: [{{ $summary['total'] }}, {{ $summary['completed'] }}, {{ $summary['pending'] }}, {{ $summary['cancelled'] }}],
                backgroundColor: ['#0d6efd','#28a745','#ffc107','#dc3545']
            }]
        }
    });

    // Export ke PDF
    function exportTableToPDF() {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF();
        doc.text("Total Booking Report", 14, 15);
        doc.autoTable({ html: '#bookingTable' });
        doc.save('total_booking_report.pdf');
    }

    // Export ke Excel
    function exportTableToExcel() {
        let table = document.getElementById("bookingTable");
        let wb = XLSX.utils.table_to_book(table, { sheet: "Report" });
        XLSX.writeFile(wb, "total_booking_report.xlsx");
    }
</script>
@endpush
