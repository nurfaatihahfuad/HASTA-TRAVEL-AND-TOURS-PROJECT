@extends ('layouts.it_admin')

@section('title', 'Blacklisted Customers')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">Blacklisted Customers</h4>
            <p class="text-muted mb-0">Manage permanently banned customers</p>
        </div>
        
        <!-- Search Bar (Top Right) -->
        <form method="GET" action="{{ route('admin.blacklisted.index') }}" class="d-inline-flex">
            <div class="input-group input-group-sm" style="width: 280px; height: 36px;">
                <input type="text" name="search" class="form-control form-control-sm" 
                    placeholder="Search blacklisted..." 
                    value="{{ $search }}">
                <button class="btn btn-outline-danger btn-sm" type="submit">
                    <i class="fas fa-search"></i>
                </button>
                @if($search)
                    <a href="{{ route('admin.blacklisted.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-times"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="metric-card bg-dark bg-opacity-10">
                <div class="metric-title">Total Blacklisted</div>
                <div class="metric-value">{{ number_format($totalBlacklisted) }}</div>
                <div class="metric-subtitle">Permanently Banned</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="metric-card bg-danger bg-opacity-10">
                <div class="metric-title">Percentage</div>
                <div class="metric-value">{{ $blacklistPercentage }}%</div>
                <div class="metric-subtitle">of Total Customers</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="metric-card">
                <div class="metric-title">Showing</div>
                <div class="metric-value">{{ $blacklistedCustomers->count() }}</div>
                <div class="metric-subtitle">on this page</div>
            </div>
        </div>
    </div>

    <!-- Blacklisted Customers Table -->
    <div class="section-card">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="mb-0">All Blacklisted Customers ({{ $blacklistedCustomers->total() }})</h6>
            <div class="text-muted">
                Page {{ $blacklistedCustomers->currentPage() }} of {{ $blacklistedCustomers->lastPage() }}
            </div>
        </div>
        
        @if($blacklistedCustomers->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Customer</th>
                            <th>Contact</th>
                            <th>Blacklist Info</th>
                            <th>Reason</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($blacklistedCustomers as $customer)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle bg-danger d-flex align-items-center justify-content-center me-3" 
                                             style="width: 40px; height: 40px;">
                                            <span class="text-white fw-bold">
                                                {{ substr($customer->name, 0, 1) }}
                                            </span>
                                        </div>
                                        <div>
                                            <div class="fw-bold">{{ $customer->name }}</div>
                                            <div class="small text-muted">
                                                ID: {{ $customer->userID }}
                                            </div>
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
                                            <small class="text-muted">+60 {{ $customer->noHP }}</small>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div class="mb-1">
                                        <span class="badge bg-dark">
                                            <i class="fas fa-ban me-1"></i> {{ $customer->blacklistID }}
                                        </span>
                                    </div>
                                    @if($customer->adminName)
                                        <div class="small">
                                            <i class="fas fa-user-shield me-1"></i>
                                            By: {{ $customer->adminName }}
                                        </div>
                                    @elseif($customer->adminID)
                                        <div class="small text-muted">
                                            Admin ID: {{ $customer->adminID }}
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div class="reason-truncate" style="max-width: 250px;">
                                        {{ Str::limit($customer->reason, 80) }}
                                        @if(strlen($customer->reason) > 80)
                                            <a href="#" class="text-danger small view-reason" 
                                               data-reason="{{ $customer->reason }}"
                                               data-bs-toggle="modal" 
                                               data-bs-target="#reasonModal">
                                                View full
                                            </a>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.customers.show', $customer->userID) }}" 
                                            class="btn btn-primary"
                                            title="View Customer Details">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        <!-- Pagination -->
                @if($blacklistedCustomers->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $blacklistedCustomers->links() }}
                    </div>
                @endif
            @else
                <div class="text-center py-5">
                    <i class="fas fa-ban fa-3x text-muted mb-3"></i>
                    <h3>No Blacklisted Customers</h3>
                    <p class="text-muted">
                        @if($search)
                            No blacklisted customers match your search criteria
                        @else
                            No customers have been blacklisted yet
                        @endif
                    </p>
                    @if($search)
                        <a href="{{ route('admin.blacklisted.index') }}" class="btn btn-danger mt-2">
                            <i class="fas fa-redo me-1"></i> Clear Filters
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>
        

            
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