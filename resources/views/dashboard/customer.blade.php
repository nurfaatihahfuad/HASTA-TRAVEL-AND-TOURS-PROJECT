<!-- resources/views/customer/dashboard.blade.php -->
@extends('layouts.customer')
@section('title', 'Customer Dashboard')

@section('content')
<!-- Welcome Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="mb-1">Welcome back, {{ auth()->user()->name }}!</h3>
        <p class="text-muted mb-0">Here's what's happening with your account.</p>
    </div>
    <div class="text-end">
        <div class="badge bg-light text-dark p-2">
            <i class="fas fa-calendar-check me-1"></i>
            {{ now()->format('l, F j, Y') }}
        </div>
    </div>
</div>

<!-- Metrics Section - Similar to Admin -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="metric-card">
            <div class="metric-title">Total Bookings</div>
            <div class="metric-value">{{ number_format($totalBookings ?? 0) }}</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="metric-card">
            <div class="metric-title">Total Days</div>
            <div class="metric-value">{{ number_format($totalDays ?? 0) }}</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="metric-card">
            <div class="metric-title">Active Bookings</div>
            <div class="metric-value">{{ number_format($activeBookings ?? 0) }}</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="metric-card">
            <div class="metric-title">Favourite Car</div>
            <div class="metric-value" style="font-size: 1.5rem;">{{ $mostCar ?? 'N/A' }}</div>
        </div>
    </div>
</div>

<!-- Main Content Row -->
<div class="row g-3">
    <!-- Recent Bookings -->
    <div class="col-lg-8">
        <div class="section-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="mb-0">Recent Bookings</h6>
                <a href="#" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            
            @if($bookings && $bookings->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Car</th>
                                <th>Pickup Date</th>
                                <th>Return Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($bookings as $booking)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-light rounded p-2 me-2">
                                                <i class="fas fa-car text-primary"></i>
                                            </div>
                                            <div>
                                                <strong>{{ $booking->carModel ?? 'Unknown' }}</strong><br>
                                                <small class="text-muted">{{ $booking->carPlate ?? '' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        {{ $booking->pickup_dateTime ? \Carbon\Carbon::parse($booking->pickup_dateTime)->format('M d, Y h:i A') : 'N/A' }}
                                    </td>
                                    <td>
                                        {{ $booking->return_dateTime ? \Carbon\Carbon::parse($booking->return_dateTime)->format('M d, Y h:i A') : 'N/A' }}
                                    </td>
                                    <td>
                                        @php
                                            $status = strtolower($booking->status ?? 'pending');
                                            $statusColors = [
                                                'confirmed' => 'success',
                                                'pending' => 'warning',
                                                'cancelled' => 'danger',
                                                'completed' => 'info'
                                            ];
                                            $color = $statusColors[$status] ?? 'secondary';
                                        @endphp
                                        <span class="badge bg-{{ $color }}">
                                            {{ ucfirst($status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary">View</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-4">
                    <div class="mb-3">
                        <i class="fas fa-calendar-times fa-3x text-muted"></i>
                    </div>
                    <h5>No bookings yet</h5>
                    <p class="text-muted">Start your first car rental journey with us!</p>
                    <a href="{{ route('browse.vehicle') }}" class="btn btn-primary">
                        <i class="fas fa-car me-1"></i> Book a Car
                    </a>
                </div>
            @endif
        </div>
        
        <!-- Quick Actions -->
        <div class="section-card">
            <h6 class="mb-3">Quick Actions</h6>
            <div class="row g-2">
                <div class="col-md-3">
                    <a href="{{ route('browse.vehicle') }}" class="btn btn-primary w-100">
                        <i class="fas fa-calendar-plus me-1"></i> Book Now
                    </a>
                </div>
                <div class="col-md-3">
                    <a href="#" class="btn btn-outline-primary w-100">
                        <i class="fas fa-history me-1"></i> History
                    </a>
                </div>
                <div class="col-md-3">
                    <a href="#" class="btn btn-outline-primary w-100">
                        <i class="fas fa-file-invoice me-1"></i> Invoices
                    </a>
                </div>
                <div class="col-md-3">
                    <a href="#" class="btn btn-outline-primary w-100">
                        <i class="fas fa-question-circle me-1"></i> Help
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Sidebar Content -->
    <div class="col-lg-4">
        <!-- Account Status -->
        <div class="section-card">
            <h6 class="mb-3">Account Status</h6>
            <div class="mb-3">
                <div class="d-flex justify-content-between mb-1">
                    <span>Verification Status</span>
                    @if($customer && $customer->customerStatus == 'active')
                        <span class="badge bg-success">Verified</span>
                    @else
                        <span class="badge bg-warning">Pending</span>
                    @endif
                </div>
                @if($customer && $customer->customerStatus != 'active')
                    <div class="alert alert-warning p-2">
                        <small>
                            <i class="fas fa-exclamation-circle me-1"></i>
                            Complete verification to access all features
                        </small>
                    </div>
                @endif
            </div>
            
            <div class="mb-3">
                <div class="d-flex justify-content-between mb-1">
                    <span>Membership</span>
                    <span class="badge bg-info">Regular</span>
                </div>
                <div class="progress" style="height: 6px;">
                    <div class="progress-bar bg-info" style="width: 60%"></div>
                </div>
                <small class="text-muted">60% to Gold membership</small>
            </div>
            
            <a href="#" class="btn btn-outline-primary w-100">
                <i class="fas fa-user-check me-1"></i> Upgrade Account
            </a>
        </div>
        
        <!-- Upcoming Bookings -->
        <div class="section-card">
            <h6 class="mb-3">Upcoming Bookings</h6>
            @if($upcomingBookings && $upcomingBookings->count() > 0)
                @foreach($upcomingBookings as $booking)
                    <div class="card mb-2 border">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="mb-1">{{ $booking->carModel ?? 'Car' }}</h6>
                                    <small class="text-muted">
                                        {{ \Carbon\Carbon::parse($booking->pickup_dateTime)->format('M d') }}
                                    </small>
                                </div>
                                <span class="badge bg-primary">
                                    {{ \Carbon\Carbon::parse($booking->pickup_dateTime)->diffForHumans() }}
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="text-center py-3">
                    <i class="fas fa-calendar-day text-muted mb-2"></i>
                    <p class="text-muted mb-0">No upcoming bookings</p>
                </div>
            @endif
        </div>

        <!-- Display most rented car -->
            @if($mostCar)
                <div class="card">
                    <div class="card-header">
                        <h5>Most Rented Car</h5>
                    </div>
                    <div class="card-body">
                        <p>Your most frequently rented vehicle: <strong>{{ $mostCar }}</strong></p>
                        
                        <!-- If you have $mostRentedVehicle object -->
                        @if(isset($mostRentedVehicle))
                            <p>Brand: {{ $mostRentedVehicle->brand ?? 'N/A' }}</p>
                            <p>Plate: {{ $mostRentedVehicle->plateNumber ?? 'N/A' }}</p>
                            <p>Times Rented: {{ $mostRentedVehicle->rental_count ?? '1' }}</p>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Display bookings -->
            @foreach($bookings as $booking)
                <tr>
                    <td>{{ $booking->bookingID }}</td>
                    <td>{{ $booking->vehicle->brand ?? 'N/A' }} {{ $booking->vehicle->model ?? 'N/A' }}</td>
                    <td>{{ $booking->vehicle->plateNumber ?? 'N/A' }}</td>
                    <!-- ... other columns -->
                </tr>
            @endforeach
        
        <!-- Support Card -->
        <div class="section-card bg-light">
            <h6 class="mb-3">Need Help?</h6>
            <p class="text-muted small mb-3">
                Our support team is here to help you with any questions.
            </p>
            <div class="d-grid gap-2">
                <a href="tel:+60123456789" class="btn btn-outline-primary">
                    <i class="fas fa-phone me-1"></i> Call Support
                </a>
                <a href="mailto:support@hasta.com" class="btn btn-outline-primary">
                    <i class="fas fa-envelope me-1"></i> Email Support
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Update dashboard metrics periodically
    function updateMetrics() {
        // You can add AJAX calls here to update metrics without page reload
        console.log('Metrics updated');
    }
    
    // Update every 60 seconds
    setInterval(updateMetrics, 60000);
});
</script>
@endpush