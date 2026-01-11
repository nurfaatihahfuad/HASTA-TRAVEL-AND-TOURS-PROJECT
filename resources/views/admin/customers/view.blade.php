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
                        @if($customer->customerStatus == 'active') bg-success
                        @elseif($customer->customerStatus == 'inactive') bg-warning
                        @elseif($customer->customerStatus == 'blacklisted') bg-dark
                        @else bg-secondary @endif mb-3">
                        {{ ucfirst($customer->customerStatus) }}
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

            @if($customer->status == 'blacklisted' && $customer->blacklistData)
            <!-- Blacklist Information Card -->
            <div class="card border-danger mb-4">
                <div class="card-header bg-danger text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-ban me-2"></i> Blacklist Information
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="small text-muted mb-1">Blacklist ID</label>
                                <div class="fw-bold">{{ $customer->blacklistData->blacklistID }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="small text-muted mb-1">Admin ID</label>
                                <div class="fw-bold">{{ $customer->blacklistData->adminID }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="small text-muted mb-1">Reason</label>
                        <div class="border rounded p-3 bg-light">
                            {{ $customer->blacklistData->reason }}
                        </div>
                    </div>
                    
                    @if(isset($customer->blacklistData->admin_name))
                    <div class="mb-3">
                        <label class="small text-muted mb-1">Blacklisted By</label>
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center me-2" 
                                style="width: 32px; height: 32px;">
                                <span class="text-white fw-bold">
                                    {{ substr($customer->blacklistData->admin_name, 0, 1) }}
                                </span>
                            </div>
                            <div>
                                <div class="fw-medium">{{ $customer->blacklistData->admin_name }}</div>
                                <small class="text-muted">Admin ID: {{ $customer->blacklistData->adminID }}</small>
                            </div>
                        </div>
                    </div>
                    @elseif($customer->blacklistData->adminID)
                    <div class="mb-3">
                        <label class="small text-muted mb-1">Blacklisted By</label>
                        <div class="text-muted">
                            Admin ID: {{ $customer->blacklistData->adminID }}
                            <br>
                            <small>(Admin name not available)</small>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        @endif

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
                            <button type="button" 
                                    class="btn btn-dark w-100"
                                    data-bs-toggle="modal" 
                                    data-bs-target="#blacklistModal">
                                <i class="fas fa-ban me-1"></i>
                                Permanently Blacklist Customer
                            </button>
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

<!-- Blacklist Modal -->
<div class="modal fade" id="blacklistModal" tabindex="-1" aria-labelledby="blacklistModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.customers.blacklist', $customer->userID) }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="blacklistModalLabel">
                        <i class="fas fa-ban text-danger me-2"></i>
                        Blacklist Customer
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Warning: This is a permanent action!</strong>
                        <ul class="mb-0 mt-2 small">
                            <li>Customer will be permanently banned</li>
                            <li>They cannot make any future bookings</li>
                            <li>This action cannot be undone</li>
                        </ul>
                    </div>
                    
                    <p class="mb-3">
                        You are about to blacklist <strong>{{ $customer->name }}</strong> (ID: {{ $customer->userID }}).
                    </p>
                    
                    <div class="mb-3">
                        <label for="reason" class="form-label required">Reason for Blacklisting</label>
                        <textarea class="form-control" 
                                  id="reason" 
                                  name="reason" 
                                  rows="4" 
                                  placeholder="Enter detailed reason for blacklisting this customer..."
                                  required
                                  minlength="10"
                                  maxlength="100"></textarea>
                        <div class="form-text">Minimum 10 characters, maximum 100 characters.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-ban me-1"></i> Confirm Blacklist
                    </button>
                </div>
            </form>
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

    // Optional: Add client-side validation for blacklist modal
    document.getElementById('blacklistModal').addEventListener('shown.bs.modal', function () {
        document.getElementById('reason').focus();
    });
    
    // Optional: Show character count for reason
    const reasonTextarea = document.getElementById('reason');
    if (reasonTextarea) {
        reasonTextarea.addEventListener('input', function() {
            const charCount = this.value.length;
            const minChars = 10;
            const maxChars = 100;
            
            if (charCount < minChars) {
                this.classList.add('is-invalid');
                this.classList.remove('is-valid');
            } else if (charCount > maxChars) {
                this.classList.add('is-invalid');
                this.classList.remove('is-valid');
            } else {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            }
        });
    }
</script>
@endpush