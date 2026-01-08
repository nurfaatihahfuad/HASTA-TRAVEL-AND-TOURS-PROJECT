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
                <a href="#" class="btn btn-sm btn-outline-danger">View All</a>
            </div>
            
            @if($bookings && $bookings->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Vehicle</th>
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
                                            <div>
                                                <strong>{{ $booking->vehicle->vehicleName ?? 'Unknown' }}</strong><br>
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
                                                'successful' => 'success',
                                                'pending' => 'warning',
                                                'rejected' => 'danger'
                                            ];
                                            $color = $statusColors[$status] ?? 'secondary';
                                        @endphp
                                        <span class="badge bg-{{ $color }}">
                                            {{ ucfirst($status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-primary">View</button>
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
                    <a href="{{ route('browse.vehicle') }}" class="btn btn-danger">
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
                    <a href="{{ route('browse.vehicle') }}" class="btn btn-danger w-100">
                        <i class="fas fa-calendar-plus me-1"></i> Book Now
                    </a>
                </div>
                <div class="col-md-3">
                    <a href="#" class="btn btn-outline-danger w-100">
                        <i class="fas fa-history me-1"></i> History
                    </a>
                </div>
                <div class="col-md-3">
                    <a href="#" class="btn btn-outline-danger w-100">
                        <i class="fas fa-file-invoice me-1"></i> Invoices
                    </a>
                </div>
                <div class="col-md-3">
                    <a href="#" class="btn btn-outline-danger w-100">
                        <i class="fas fa-question-circle me-1"></i> Help
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Sidebar Content -->
    <div class="col-lg-4">
        
        <!-- Upcoming Bookings -->
        <div class="section-card">
            <h6 class="mb-3">Upcoming Bookings</h6>
            @if($upcomingBookings && $upcomingBookings->count() > 0)
                @foreach($upcomingBookings as $booking)
                    <div class="card mb-2 border">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="mb-1">{{ $booking->vehicle->vehicleName ?? 'Car' }}</h6>
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
                <div class="section-card">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="mb-0">Most Rented Car</h6>
                        @php
                            $rentalCount = auth()->user()->bookings()
                                ->join('vehicles', 'booking.vehicleID', '=', 'vehicles.vehicleID')
                                ->where('vehicles.vehicleName', $mostCar)
                                ->count();
                        @endphp
                        @if($rentalCount > 1)
                            <span class="badge bg-danger">{{ $rentalCount }} times</span>
                        @endif
                    </div>

                    <!-- Get vehicle details with image -->
                    @php
                        $mostRentedDetails = auth()->user()->bookings()
                            ->join('vehicles', 'booking.vehicleID', '=', 'vehicles.vehicleID')
                            ->where('vehicles.vehicleName', $mostCar)
                            ->select('vehicles.*')
                            ->first();
                    @endphp

                    <div class="text-center">
                    <!-- Vehicle Image -->
                    @if($mostRentedDetails && $mostRentedDetails->image_url)
                        <div class="mb-3">
                            <img src="{{ asset('storage/' . $mostRentedDetails->image_url) }}" 
                                alt="{{ $mostRentedDetails->vehicleName }}" 
                                class="img-fluid rounded" 
                                style="max-height: 150px; width: auto;">
                        </div>
                    @else
                        <!-- Default car icon if no image -->
                        <div class="mb-3">
                            <div class="bg-light rounded-circle d-flex align-items-center justify-content-center" 
                                style="width: 150px; height: 150px; margin: 0 auto;">
                                <i class="fas fa-car fa-3x text-danger"></i>
                            </div>
                        </div>
                    @endif
                    
                    <h5 class="mb-1">{{ $mostCar }}</h5>
                    @if($mostRentedDetails)
                        <div class="row text-start small mt-3">
                            <div class="col-6">
                                <span class="text-muted">Vehicle:</span>
                                <div class="fw-semibold">{{ $mostRentedDetails->vehicleName ?? 'N/A' }}</div>
                            </div>
                            <div class="col-6">
                                <span class="text-muted">Plate:</span>
                                <div class="fw-semibold">{{ $mostRentedDetails->plateNo ?? 'N/A' }}</div>
                            </div>
                        </div>
                    @endif
                </div>
            @endif

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