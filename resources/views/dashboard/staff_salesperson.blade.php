<!-- Salesperson Dashboard -->
@extends('layouts.salesperson')
@section('title', 'Staff Dashboard')

@section('content')
<div class="container-fluid">
    <!-- Main -->
    <h3 class="mb-4">Welcome, {{ auth()->user()->name }}</h3>

    <!-- KPI cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="metric-card">
                <div class="metric-title">New Booking Today</div>
                <div class="metric-value">{{ $bookingsToday }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="metric-card">
                <div class="metric-title">Pending Payments</div>
                <div class="metric-value">{{ $pendingPayments ?? $statusPending }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="metric-card">
                <div class="metric-title">Completed Bookings</div>
                <div class="metric-value">{{ $statusCompleted }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="metric-card">
                <div class="metric-title">Active Bookings</div>
                <div class="metric-value">{{ $statusBooked }}</div>
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

    <!-- Latest bookings -->
    <div class="section-card">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="mb-0">Latest Bookings</h6>
            <a href="{{ route('record.payment') }}" class="btn btn-sm btn-outline-primary">
                View All Payments <i class="fas fa-arrow-right ms-1"></i>
            </a>
        </div>
        
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Customer</th>
                        <th>Vehicle</th>
                        <th>Payment Proof</th>
                        <!--<th>Payment Status</th> -->
                        <th>Payment Actions</th>
                        <th>Amount</th>
                        <th>Created At</th>
                        <th>Booking Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($latestBookings as $b)
                        <tr>
                            <!-- Customer Column -->
                            <td>
                                <strong>{{ $b->name }}</strong>
                            </td>
                            
                            <!-- Vehicle Column -->
                            <td>
                                <div>{{ $b->vehicleName }}</div>
                                <small class="text-muted">{{ $b->plateNo }}</small>
                            </td>
                            
                            <!-- Payment Proof Column -->
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
                            
                            <!-- Payment Status Column -->
                            <!--
                            <td>
                                <span class="badge 
                                    @if($b->paymentStatus == 'approved') bg-success
                                    @elseif($b->paymentStatus == 'pending') bg-warning
                                    @elseif($b->paymentStatus == 'rejected') bg-danger
                                    @else bg-secondary @endif">
                                    {{ $b->paymentStatus ?? 'No payment' }}
                                </span>
                            </td> -->
                            
                            <!-- Payment Actions Column -->
                            <td>
                                <div class="btn-group" role="group">
                                    @if($b->paymentStatus == 'pending')
                                        @php
                                            $payment = \App\Models\Payment::where('bookingID', $b->bookingID)->first();
                                        @endphp
                                        
                                        @if($payment)
                                            <!-- Separate forms untuk approve dan reject -->
                                            <form method="POST" action="{{ route('payment.approve', $payment->paymentID) }}" 
                                                class="d-inline" id="approveForm{{ $payment->paymentID }}">
                                                @csrf
                                            </form>
                                            
                                            <form method="POST" action="{{ route('payment.reject', $payment->paymentID) }}" 
                                                class="d-inline" id="rejectForm{{ $payment->paymentID }}">
                                                @csrf
                                            </form>
                                            
                                            <button type="button" 
                                                    class="btn btn-success btn-sm"
                                                    onclick="if(confirm('Approve this payment?')) document.getElementById('approveForm{{ $payment->paymentID }}').submit();"
                                                    title="Approve Payment">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            
                                            <button type="button" 
                                                    class="btn btn-danger btn-sm"
                                                    onclick="if(confirm('Reject this payment?')) document.getElementById('rejectForm{{ $payment->paymentID }}').submit();"
                                                    title="Reject Payment">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        @else
                                            <span class="badge bg-secondary">No Payment</span>
                                        @endif
                                    @else
                                        <span class="badge bg-{{ $b->paymentStatus == 'approved' ? 'success' : 'danger' }}">
                                            {{ ucfirst($b->paymentStatus) }}
                                        </span>
                                    @endif
                                </div>
                            </td>
                            
                            <!-- Amount Column -->
                            <td>
                                @if(isset($b->amountPaid) && $b->amountPaid)
                                    <strong>RM {{ number_format($b->amountPaid, 2) }}</strong>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            
                            <!-- Created At Column -->
                            <td>
                                {{ \Carbon\Carbon::parse($b->created_at)->format('M d, Y') }}
                                <br>
                                <small class="text-muted">
                                    {{ \Carbon\Carbon::parse($b->created_at)->format('h:i A') }}
                                </small>
                            </td>
                            
                            <!-- Booking Actions Column -->
                            <td>
                                <div class="btn-group" role="group">
                                    <!-- Quick Approve/Reject Booking -->
                                    @if($b->bookingStatus == 'pending')
                                        <!-- HANYA check booking status -->
                                        <form method="POST" action="{{ route('booking.updateStatus', $b->bookingID) }}" class="d-inline">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" name="status" value="successful" 
                                                    class="btn btn-success btn-sm" 
                                                    onclick="return confirm('Approve this booking?')"
                                                    title="Approve Booking">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            <button type="submit" name="status" value="rejected" 
                                                    class="btn btn-danger btn-sm"
                                                    onclick="return confirm('Reject this booking?')"
                                                    title="Reject Booking">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </form>
                                    @else
                                        <!-- Show booking status badge -->
                                         <span class="badge
                                            @if($b->bookingStatus == 'pending') bg-warning
                                            @elseif($b->bookingStatus == 'successful') bg-info
                                            @elseif($b->bookingStatus == 'completed') bg-success
                                            @elseif($b->bookingStatus == 'rejected') bg-danger
                                            @else bg-secondary
                                            @endif">
                                            {{ ucfirst($b->bookingStatus) }}
                                        </span>
                                    @endif
                                    
                                    <!-- View Details -->
                                    <button type="button" 
                                            class="btn btn-primary btn-sm"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#bookingModal{{ $b->bookingID }}">
                                        <i class="fas fa-info-circle"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>

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
                                        
                                        <!-- Booking Actions in Modal -->
                                        @if($b->bookingStatus == 'pending')
                                            <form method="POST" action="{{ route('booking.updateStatus', $b->bookingID ) }}" class="d-inline">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" name="status" value="successful" 
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
                                        
                                        <!-- Payment Actions in Modal -->
                                        @if($b->paymentStatus == 'pending')
                                            @php
                                                $payment = \App\Models\Payment::where('bookingID', $b->bookingID)->first();
                                            @endphp
                                            
                                            @if($payment)
                                                <div class="vr mx-2"></div>
                                                <span class="text-muted me-2">Payment Actions:</span>
                                                <form method="POST" action="{{ route('payment.approve', $payment->paymentID) }}" class="d-inline">
                                                    @csrf
                                                    <button type="submit" 
                                                            class="btn btn-outline-success"
                                                            onclick="return confirm('Approve this payment?')">
                                                        <i class="fas fa-check me-1"></i> Approve
                                                    </button>
                                                </form>
                                                
                                                <form method="POST" action="{{ route('payment.reject', $payment->paymentID) }}" class="d-inline">
                                                    @csrf
                                                    <button type="submit" 
                                                            class="btn btn-outline-danger"
                                                            onclick="return confirm('Reject this payment?')">
                                                        <i class="fas fa-times me-1"></i> Reject
                                                    </button>
                                                </form>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
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
            labels: ['Completed', 'Booked', 'Pending'],
            datasets: [{
                data: [{{ $statusCompleted }}, {{ $statusBooked }}, {{ $statusPending }}],
                backgroundColor: ['#28a745', '#0d6efd', '#ffc107']
            }]
        },
        options: { responsive: true }
    });
});
</script>
@endsection