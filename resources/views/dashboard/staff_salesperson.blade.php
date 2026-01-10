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
                        <div class="metric-title">New Booking Today</div>
                        <div class="metric-value">{{ $bookingsToday }}</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="metric-card">
                        <div class="metric-title">Pending Payments</div>
                        <div class="metric-value">{{ $statusPending }}</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="metric-card">
                        <div class="metric-title">Cancelled Bookings</div>
                        <div class="metric-value">{{ $statusCancelled }}</div>
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
                <h6 class="mb-3">Latest bookings</h6>
                <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Customer</th>
                        <th>Vehicle</th>
                        <th>Payment Proof</th>
                        <th>Payment Status</th>
                        <th>Amount</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($latestBookings as $b)
                        <tr>
                            <td>
                                <strong>{{ $b->name }}</strong>
                            </td>
                            <td>
                                <div>{{ $b->vehicleName }}</div>
                                <small class="text-muted">{{ $b->plateNo }}</small>
                            </td>
                            <td>
                                @if(!empty($b->receipt_file_path))
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('receipt.view', ['bookingID' => $b->bookingID]) }}" 
                                           target="_blank"
                                           class="btn btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
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
                                <span class="badge 
                                    @if($b->paymentStatus == 'approved') bg-success
                                    @elseif($b->paymentStatus == 'pending') bg-warning
                                    @elseif($b->paymentStatus == 'rejected') bg-danger
                                    @else bg-secondary @endif">
                                    {{ $b->paymentStatus ?? 'No payment' }}
                                </span>
                            </td> 
                            <td>
                                @if(isset($b->amountPaid) && $b->amountPaid)
                                    <strong>RM {{ number_format($b->amountPaid, 2) }}</strong>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                {{ \Carbon\Carbon::parse($b->created_at)->format('M d, Y') }}
                                <br>
                                <small class="text-muted">
                                    {{ \Carbon\Carbon::parse($b->created_at)->format('h:i A') }}
                                </small>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <!-- Quick Approve/Reject -->
                                    @if($b->paymentStatus == 'pending' && $b->bookingStatus == 'pending')
                                        <form method="POST" action="{{ route('booking.updateStatus', $b->bookingID) }}" class="d-inline">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" name="status" value="successful" 
                                                    class="btn btn-success btn-sm" 
                                                    onclick="return confirm('Approve this booking?')"
                                                    title="Approve">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            <button type="submit" name="status" value="rejected" 
                                                    class="btn btn-danger btn-sm"
                                                    onclick="return confirm('Reject this booking?')"
                                                    title="Reject">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </form>
                                    @endif
                                    
                                    <!-- View Details -->
                                    <button type="button" 
                                            class="btn btn-primary btn-sm"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#bookingModal{{ $b->bookingID }}">
                                        <i class="fas fa-info-circle"></i>
                                    </button>
                                </div>

                                <!-- Modal for Details -->
                                <div class="modal fade" id="bookingModal{{ $b->bookingID }}" tabindex="-1">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Booking Details - {{ $b->bookingID }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <h6>Customer Information</h6>
                                                        <p><strong>Name:</strong> {{ $b->name }}</p>
                                                        <p><strong>User ID:</strong> {{ $b->userID }}</p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <h6>Vehicle Information</h6>
                                                        <p><strong>Vehicle:</strong> {{ $b->vehicleName }}</p>
                                                        <p><strong>Plate No:</strong> {{ $b->plateNo }}</p>
                                                    </div>
                                                </div>
                                                <div class="row mt-3">
                                                    <div class="col-md-6">
                                                        <h6>Booking Dates</h6>
                                                        <p><strong>Pickup:</strong> {{ \Carbon\Carbon::parse($b->pickup_dateTime)->format('M d, Y h:i A') }}</p>
                                                        <p><strong>Return:</strong> {{ \Carbon\Carbon::parse($b->return_dateTime)->format('M d, Y h:i A') }}</p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <h6>Payment Information</h6>
                                                        <!--<p><strong>Status:</strong> 
                                                            <span class="badge bg-{{ $b->paymentStatus == 'approved' ? 'success' : ($b->paymentStatus == 'pending' ? 'warning' : 'danger') }}">
                                                                {{ $b->paymentStatus ?? 'No payment' }}
                                                            </span>
                                                        </p> -->
                                                        <p><strong>Amount:</strong> RM {{ number_format($b->amountPaid ?? 0, 2) }}</p>
                                                        @if($b->receipt_file_path)
                                                            <p><strong>Receipt:</strong> 
                                                                <a href="{{ asset('storage/' . $b->receipt_file_path) }}" target="_blank">View Receipt</a>
                                                            </p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                @if($b->paymentStatus == 'pending')
                                                    <form method="POST" action="{{ route('booking.updateStatus', $b->bookingID ) }}" class="d-inline">
                                                        @csrf
                                                        @method('PUT')
                                                        <button type="submit" name="status" value="approved" 
                                                                class="btn btn-success"
                                                                onclick="return confirm('Approve this booking?')">
                                                            <i class="fas fa-check me-1"></i> Approve Booking
                                                        </button>
                                                        <button type="submit" name="status" value="rejected" 
                                                                class="btn btn-danger"
                                                                onclick="return confirm('Reject this booking?')">
                                                            <i class="fas fa-times me-1"></i> Reject Booking
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="fas fa-clipboard-list fa-2x mb-3"></i>
                                    <h5>No bookings found</h5>
                                    <p>No bookings match your criteria</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
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

