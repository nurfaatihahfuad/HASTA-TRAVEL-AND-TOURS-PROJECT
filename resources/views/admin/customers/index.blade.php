@extends('layouts.it_admin')

@section('title', 'Customer Management')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">Customer Management</h4>
            <p class="text-muted mb-0">Manage and view all customer accounts</p>
        </div>
    </div>

    <!-- Statistics & Filters Row -->
    <div class="row g-3 mb-4">
        <!-- Statistics Cards - All same height -->
        <div class="col-md-4">
            <div class="metric-card h-100">
                <div class="metric-title">Total Customers</div>
                <div class="metric-value">{{ number_format($totalCustomers) }}</div>
                <div class="metric-subtitle">Registered Customers</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="metric-card h-100 bg-success bg-opacity-10">
                <div class="metric-title">Active Customers</div>
                <div class="metric-value">{{ number_format($activeCustomers) }}</div>
                <div class="metric-subtitle">Currently Active</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="metric-card h-100">
                <div class="metric-title">Showing</div>
                <div class="metric-value">{{ $customers->count() }}</div>
                <div class="metric-subtitle">on this page</div>
            </div>
        </div>
    </div>

    <!-- Filters Card - Full width but same height structure -->
    <div class="metric-card mb-4">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h6 class="mb-0">Filters</h6>
                @if(request()->hasAny(['search', 'status']))
                <div class="mt-2">
                    <small class="text-muted d-inline-block me-2">Active filters:</small>
                    <div class="d-inline-flex gap-1">
                        @if(request('search'))
                            <span class="badge bg-info py-1 px-2">{{ request('search') }}</span>
                        @endif
                        @if(request('status'))
                            <span class="badge bg-primary py-1 px-2">{{ ucfirst(request('status')) }}</span>
                        @endif
                    </div>
                </div>
                @endif
            </div>
            <div class="col-md-4 text-end">
                @if(request()->hasAny(['search', 'status']))
                <a href="{{ route('admin.customers.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-redo me-1"></i> Clear All
                </a>
                @endif
            </div>
        </div>
        
        <form method="GET" action="{{ route('admin.customers.index') }}" class="row g-3 mt-3 align-items-end">
            <div class="col-md-5">
                <label class="form-label small mb-1">Search</label>
                <div class="input-group input-group-sm">
                    <input type="text" name="search" class="form-control" 
                           placeholder="Search by name, email, phone..." 
                           value="{{ request('search') }}">
                    <button class="btn btn-outline-secondary" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
            
            <div class="col-md-4">
                <label class="form-label small mb-1">Status</label>
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    <option value="blacklisted" {{ request('status') == 'blacklisted' ? 'selected' : '' }}>Blacklisted</option>
                </select>
            </div>
            
            <div class="col-md-3">
                <button type="submit" class="btn btn-danger w-100">
                    <i class="fas fa-filter me-1"></i> Apply Filters
                </button>
            </div>
        </form>
    </div>

    <!-- Customers Table -->
    <div class="section-card">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="mb-0">All Customers ({{ $customers->total() }})</h6>
            <div class="text-muted">
                Page {{ $customers->currentPage() }} of {{ $customers->lastPage() }}
            </div>
        </div>
        
        @if($customers->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Customer ID</th>
                            <th>Name</th>
                            <th>Contact Information</th>
                            <th>Bookings</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($customers as $customer)
                            <tr>
                                <td>
                                    <small class="text-muted">#{{ $customer->customerID ?? $customer->userID }}</small>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                            <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center me-2" 
                                                 style="width: 36px; height: 36px;">
                                                <span class="text-white fw-bold">
                                                    {{ substr($customer->name, 0, 1) }}
                                                </span>
                                            </div>
                                        <div>
                                            <strong>{{ $customer->name }}</strong>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="mb-1">
                                        <i class="fas fa-envelope text-muted me-1" style="font-size: 0.8rem;"></i>
                                        <span>{{ $customer->email }}</span>
                                    </div>
                                    @if($customer->noHP)
                                        <div>
                                            <i class="fas fa-phone text-muted me-1" style="font-size: 0.8rem;"></i>
                                            <small class="text-muted">{{ $customer->noHP }}</small>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="fw-bold">{{ $customer->total_bookings ?? 0 }}</span>
                                        <small class="text-muted">
                                            {{ $customer->successful_bookings ?? 0 }} successful
                                        </small>
                                        @if($customer->last_booking_date)
                                            <small class="text-muted">
                                                Last: {{ \Carbon\Carbon::parse($customer->last_booking_date)->format('M d') }}
                                            </small>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <span class="badge 
                                        @if($customer->status == 'active') bg-success
                                        @elseif($customer->status == 'pending') bg-warning
                                        @elseif($customer->status == 'rejected') bg-danger
                                        @elseif($customer->status == 'blacklisted') bg-dark
                                        @else bg-secondary @endif">
                                        {{ ucfirst($customer->status) }}
                                    </span>
                                </td>
                                <td>
                                    <!-- View Button with icon + text -->
                                    <a href="{{ route('admin.customers.show', $customer->userID) }}" 
                                    class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-eye me-1"></i> View
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($customers->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $customers->links() }}
                </div>
            @endif
        @else
            <div class="text-center py-5">
                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                <h3>No Customers Found</h3>
                <p class="text-muted">
                    @if(request()->hasAny(['search', 'status', 'date_from', 'date_to']))
                        No customers match your search criteria
                    @else
                        No customers registered yet
                    @endif
                </p>
                @if(request()->hasAny(['search', 'status', 'date_from', 'date_to']))
                    <a href="{{ route('admin.customers.index') }}" class="btn btn-danger mt-2">
                        <i class="fas fa-redo me-1"></i> Clear Filters
                    </a>
                @endif
            </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Consistent metric card styling */
    .metric-card {
        background: white;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        transition: transform 0.2s;
        min-height: 120px; /* Fixed minimum height */
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    .metric-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }
    .metric-title {
        font-size: 0.875rem;
        color: #6c757d;
        margin-bottom: 5px;
        font-weight: 500;
    }
    .metric-value {
        font-size: 1.8rem;
        font-weight: bold;
        color: #212529;
        line-height: 1;
        margin-bottom: 5px;
    }
    .metric-subtitle {
        font-size: 0.75rem;
        color: #6c757d;
        margin-top: 5px;
    }
    
    /* For the filter card (special styling) */
    .row.g-3 .metric-card {
        min-height: 120px; /* Match statistics cards height */
    }
    
    /* Special styling for the filter section card */
    .section-card {
        background: white;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        margin-bottom: 20px;
    }
    
    /* Table styles */
    .table th {
        font-weight: 600;
        color: #495057;
        border-bottom-width: 2px;
    }
    .table td {
        vertical-align: middle;
    }
    .btn-group .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }
    
    /* Ensure all statistics cards have same height */
    .h-100 {
        height: 100% !important;
    }
</style>
@endpush