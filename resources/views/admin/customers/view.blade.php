@extends('layouts.it_admin')

@section('title', 'Customer Details - ' . $customer->name)

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">Customer Details</h4>
            <p class="text-muted mb-0">View and manage customer information</p>
        </div>
        <div>
            <a href="{{ route('admin.customers.index') }}" class="btn btn-outline-danger">
                <i class="fas fa-arrow-left me-1"></i> Back to Customers
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Left Column: Customer Info -->
        <div class="col-lg-4">
            <!-- Customer Profile Card -->
            <div class="card mb-4">
                <div class="card-body text-center">
                    <!-- Profile Picture -->
                    <div class="mb-3">
                            <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center mx-auto" 
                                 style="width: 150px; height: 150px;">
                                <span class="text-white fw-bold" style="font-size: 3rem;">
                                    {{ substr($customer->name, 0, 1) }}
                                </span>
                            </div>
                    </div>
                    
                    <h4 class="mb-1">{{ $customer->name }}</h4>
                    <p class="text-muted mb-2">Customer ID: #{{ $customer->userID }}</p>
                    
                    <!-- Status Badge -->
                    <span class="badge 
                        @if($customer->status == 'active') bg-success
                        @elseif($customer->status == 'inactive') bg-warning
                        @else bg-danger @endif mb-3">
                        {{ ucfirst($customer->status) }}
                    </span>
                    
                    <!-- Quick Stats -->
                    <div class="row mt-4">
                        <div class="col-4">
                            <div class="border rounded p-2">
                                <div class="text-danger fw-bold">{{ $totalBookings }}</div>
                                <small class="text-muted">Bookings</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="border rounded p-2">
                                <div class="text-success fw-bold">RM {{ number_format($totalSpent, 0) }}</div>
                                <small class="text-muted">Spent</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="border rounded p-2">
                                <div class="text-info fw-bold">
                                    @if($favoriteCar)
                                        {{ substr($favoriteCar, 0, 8) }}...
                                    @else
                                        N/A
                                    @endif
                                </div>
                                <small class="text-muted">Favorite</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Contact Information</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="small text-muted mb-1">Email Address</label>
                        <div class="d-flex align-items-center">
                            <i class="fas fa-envelope text-danger me-2"></i>
                            <span>{{ $customer->email }}</span>
                        </div>
                    </div>
                    
                    @if($customer->noHP)
                    <div class="mb-3">
                        <label class="small text-muted mb-1">Phone Number</label>
                        <div class="d-flex align-items-center">
                            <i class="fas fa-phone text-danger me-2"></i>
                            <span>+60 {{ $customer->noHP }}</span>
                        </div>
                    </div>
                    @endif
                  
                </div>
            </div>

            <!-- Actions -->
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title mb-3">Quick Actions</h6>
                    <div class="d-grid gap-2">
                        <!--<button type="button" class="btn btn-outline-danger"
                                onclick="confirmStatusChange('{{ $customer->userID }}')">
                            <i class="fas fa-ban me-1"></i> 
                            @if($customer->status == 'active')
                                Suspend Account
                            @elseif($customer->status == 'suspended')
                                Activate Account
                            @else
                                Change Status
                            @endif
                        </button>-->
                        <!-- Blacklist Button (Only show if customer is active) -->
                        @if($customer->status == 'active')
                            <form method="POST" action="{{ route('admin.customers.toggle-status', $customer->userID) }}">
                                @csrf
                                <button type="submit" 
                                        class="btn btn-dark w-100"
                                        onclick="return confirmPermanentBlacklist('{{ $customer->name }}')">
                                    <i class="fas fa-ban me-1"></i>
                                    Permanently Blacklist Customer
                                </button>
                            </form>
                        @elseif($customer->status == 'blacklisted')
                            <!-- Show disabled button for blacklisted customers -->
                            <button type="button" class="btn btn-dark w-100" disabled>
                                <i class="fas fa-lock me-1"></i>
                                Customer is Permanently Blacklisted
                            </button>
                            <div class="alert alert-dark small mb-0">
                                <i class="fas fa-exclamation-triangle me-1"></i>
                                This action cannot be reversed. Customer cannot make any bookings.
                            </div>
                        @else
                            <!-- For other statuses (pending, rejected) -->
                            <button type="button" class="btn btn-secondary w-100" disabled>
                                <i class="fas fa-info-circle me-1"></i>
                                Customer must be active to blacklist
                            </button>
                        @endif

                        <a href="mailto:{{ $customer->email }}" class="btn btn-outline-primary">
                            <i class="fas fa-envelope me-1"></i> Send Email
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Customer Activity -->
        <div class="col-lg-8">
            <!-- Tabs Navigation -->
            <ul class="nav nav-tabs mb-4" id="customerTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="bookings-tab" data-bs-toggle="tab" 
                            data-bs-target="#bookings" type="button" role="tab">
                        <i class="fas fa-calendar-check me-1"></i> Booking History
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="payments-tab" data-bs-toggle="tab" 
                            data-bs-target="#payments" type="button" role="tab">
                        <i class="fas fa-credit-card me-1"></i> Payment History
                    </button>
                </li>
            </ul>

            <!-- Tabs Content -->
            <div class="tab-content" id="customerTabContent">
                <!-- Bookings Tab -->
                <div class="tab-pane fade show active" id="bookings" role="tabpanel">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="card-title mb-4">Booking History ({{ $bookings->count() }})</h6>
                            
                            @if($bookings->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Booking ID</th>
                                                <th>Vehicle</th>
                                                <th>Dates</th>
                                                <th>Status</th>
                                                <th>Payment</th>
                                                <th>Amount</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($bookings as $booking)
                                            <tr>
                                                <td>
                                                    <small class="text-muted">{{ $booking->bookingID }}</small>
                                                </td>
                                                <td>
                                                    <div>{{ $booking->vehicleName }}</div>
                                                    <small class="text-muted">{{ $booking->plateNo }}</small>
                                                </td>
                                                <td>
                                                    <div>Pickup: {{ \Carbon\Carbon::parse($booking->pickup_dateTime)->format('M d') }}</div>
                                                    <small>Return: {{ \Carbon\Carbon::parse($booking->return_dateTime)->format('M d') }}</small>
                                                </td>
                                                <td>
                                                    <span class="badge 
                                                        @if($booking->bookingStatus == 'booked') bg-success
                                                        @elseif($booking->bookingStatus == 'pending') bg-warning
                                                        @else bg-danger @endif">
                                                        {{ ucfirst($booking->bookingStatus) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge 
                                                        @if($booking->paymentStatus == 'approved') bg-success
                                                        @elseif($booking->paymentStatus == 'pending') bg-warning
                                                        @else bg-danger @endif">
                                                        {{ ucfirst($booking->paymentStatus) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    RM {{ number_format($booking->amountPaid ?? 0, 2) }}
                                                </td>
                                                <td>
                                                    <a href="#" class="btn btn-sm btn-primary">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="fas fa-calendar-times fa-2x text-muted mb-3"></i>
                                    <h5>No Booking History</h5>
                                    <p class="text-muted">This customer has not made any bookings yet.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Payments Tab -->
                <div class="tab-pane fade" id="payments" role="tabpanel">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="card-title mb-4">Payment History</h6>
                            
                            @if($bookings->where('amountPaid', '>', 0)->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Payment Date</th>
                                                <th>Booking ID</th>
                                                <th>Amount</th>
                                                <th>Status</th>
                                                <th>Receipt</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($bookings->where('amountPaid', '>', 0) as $booking)
                                            <tr>
                                                <td>{{ \Carbon\Carbon::parse($booking->created_at)->format('M d, Y h:i A') }}</td>
                                                <td>{{ $booking->bookingID }}</td>
                                                <td class="fw-bold">RM {{ number_format($booking->amountPaid, 2) }}</td>
                                                <td>
                                                    <span class="badge bg-{{ $booking->paymentStatus == 'approved' ? 'success' : 'warning' }}">
                                                        {{ ucfirst($booking->paymentStatus) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @if($booking->receipt_file_path)
                                                        <a href="{{ asset('storage/' . $booking->receipt_file_path) }}" 
                                                           target="_blank" class="btn btn-sm btn-info">
                                                            <i class="fas fa-eye"></i> View
                                                        </a>
                                                    @else
                                                        <span class="badge bg-warning">No receipt</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="fas fa-credit-card fa-2x text-muted mb-3"></i>
                                    <h5>No Payment History</h5>
                                    <p class="text-muted">No payment records found for this customer.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .nav-tabs .nav-link {
        color: #6c757d;
        border: none;
        padding: 0.75rem 1.5rem;
    }
    .nav-tabs .nav-link.active {
        color: #dc3545;
        border-bottom: 3px solid #dc3545;
        background-color: transparent;
    }
    .nav-tabs .nav-link:hover {
        color: #dc3545;
    }
</style>
@endpush

@push('scripts')
<script>
    // Initialize tabs
    var triggerTabList = [].slice.call(document.querySelectorAll('#customerTab button'))
    triggerTabList.forEach(function (triggerEl) {
        var tabTrigger = new bootstrap.Tab(triggerEl)
        triggerEl.addEventListener('click', function (event) {
            event.preventDefault()
            tabTrigger.show()
        })
    })

    function confirmPermanentBlacklist(customerName) {
        return confirm(`⚠️ PERMANENT ACTION ⚠️\n\nBlacklist ${customerName}?\n\n❌ This action CANNOT be undone\n❌ Customer will be permanently banned\n❌ They cannot make any future bookings`);
    }
    
</script>
@endpush