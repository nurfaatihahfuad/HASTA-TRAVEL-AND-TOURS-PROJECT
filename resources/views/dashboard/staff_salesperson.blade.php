<!-- Salesperson Dashboard -->
@extends('layouts.salesperson')
@section('title', 'Staff Dashboard')

@section('content')

        <!-- Main -->
            <h3 class="mb-4">Welcome, {{ auth()->user()->name }}</h3>

            <!-- KPI cards -->
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="metric-card">
                        <div class="metric-title">Assigned today</div>
                        <div class="metric-value"></div>
                        <div class="metric-delta">↑ 4.1%</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="metric-card">
                        <div class="metric-title">Pending payments</div>
                        <div class="metric-value"></div>
                        <div class="metric-delta">↑ 2.5%</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="metric-card">
                        <div class="metric-title">Cancelled bookings</div>
                        <div class="metric-value">{{ $statusCancelled }}</div>
                        <div class="metric-delta">↓ 1.2%</div>
                    </div>
                </div>
            </div>

            <!-- Charts + Booking status -->
            <div class="row g-3 mb-4">
                <div class="col-md-8">
                    <div class="section-card">
                        <h6 class="mb-3">Weekly bookings</h6>
                        <canvas id="salesBookingBar"></canvas>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="section-card">
                        <h6 class="mb-3">Booking status</h6>
                        <canvas id="salesStatusPie"></canvas>
                    </div>
                </div>
            </div>

            <!-- All bookings -->
            <div class="section-card">
                <h6 class="mb-3">All bookings</h6>
                <div class="table-responsive">
                    <table class="table table-sm align-middle">
                        <thead>
                            <tr>
                                <th>Booking ID</th>
                                <th>Car</th>
                                <!--<th>Payment Proof</th>
                                <th>Status</th>
                                <th>Created At</th>-->
                                <th>Payment Proof</th>
                                <th>Payment Status</th>
                                <th>Amount Paid</th>
                                <th>Booking Status</th>
                                <th>Created At</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bookings as $b)
                                <tr>
                                    <td>{{ $b->bookingID }}</td>
                                    <td>{{ $b->vehicleID }}</td>
                                    <td>
                                        @if(!empty($b->receipt_file_path))
                                            <div class="btn-group btn-group-sm">
                                                <!-- View in browser -->
                                                <a href="{{ route('receipt.view', ['bookingID' => $b->bookingID]) }}" 
                                                target="_blank"
                                                class="btn btn-info">
                                                    <i class="fas fa-eye"></i> View
                                                </a>
                                                
                                                <!-- Download -->
                                                <a href="{{ route('receipt.download', ['bookingID' => $b->bookingID]) }}"
                                                class="btn btn-outline-primary">
                                                    <i class="fas fa-download"></i>
                                                </a>
                                            </div>
                                        @else
                                            <span class="badge bg-warning">No receipt</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($b->paymentStatus)
                                            <span class="badge 
                                                @if($b->paymentStatus == 'approved') bg-success
                                                @elseif($b->paymentStatus == 'pending') bg-warning
                                                @elseif($b->paymentStatus == 'rejected') bg-danger
                                                @else bg-secondary @endif">
                                                {{ $b->paymentStatus ?? 'N/A' }}
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">No payment</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if(isset($b->amountPaid) && $b->amountPaid)
                                            RM {{ number_format($b->amountPaid, 2) }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        <form method="POST" action="{{ route('booking.updateStatus', $b->bookingID) }}">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" name="status" value="approved" class="btn btn-success btn-sm">
                                                Approve
                                            </button>
                                            <button type="submit" name="status" value="rejected" class="btn btn-danger btn-sm">
                                                Reject
                                            </button>
                                        </form>
                                    </td>
                                    <td>{{ $b->created_at }}</td>
                                </tr> 
                                @empty
                                <tr><td colspan="4">No bookings found.</td></tr>
                            @endforelse  
                        </tbody>
                    </table>
                </div>
            </div>
    </div>
</div>

{{-- Charts --}}
<script>
document.addEventListener('DOMContentLoaded', () => {
    const ctxBar = document.getElementById('salesBookingBar');
    new Chart(ctxBar, {
        type: 'bar',
        data: {
            labels: @json($weeklyLabels),
            datasets: [{
                label: 'Bookings',
                data: @json($weeklyData),
                backgroundColor: '#0d6efd',
                borderRadius: 6
            }]
        },
        options: { responsive: true, plugins: { legend: { display: false } } }
    });

    const ctxPie = document.getElementById('salesStatusPie');
    new Chart(ctxPie, {
        type: 'pie',
        data: {
            labels: ['Cancelled', 'Booked', 'Pending'],
            datasets: [{
                data: [{{ $statusCancelled }}, {{ $statusBooked }}, {{ $statusPending }}],
                backgroundColor: ['#adb5bd', '#0d6efd', '#ffc107']
            }]
        },
        options: { responsive: true }
    });
});
</script>
@endsection

