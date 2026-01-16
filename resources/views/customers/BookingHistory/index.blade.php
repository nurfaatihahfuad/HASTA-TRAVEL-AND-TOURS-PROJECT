@extends('layouts.customer')

@section('title', 'Booking History')

@push('styles')
<style>
    .card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        transition: transform 0.2s;
    }
    
    .card:hover {
        transform: translateY(-2px);
    }

    .filter-minimal {
        background: white;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 25px;
        border: 1px solid #f0f0f0;
    }
    
    .filter-minimal .form-control {
        border-radius: 8px;
        border: 1px solid #e0e0e0;
        padding: 0.5rem 1rem;
    }
    
    .status-tabs {
        border-bottom: 1px solid #f0f0f0;
        padding-bottom: 15px;
        margin-bottom: 20px;
    }
    
    .status-tab {
        border: none;
        background: none;
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 0.875rem;
        color: #666;
        transition: all 0.3s;
        margin-right: 8px;
    }
    
    .status-tab:hover {
        background: #f8f9fa;
        color: #333;
    }
    
    .status-tab.active {
        background: #dc3545;
        color: white;
        font-weight: 500;
    }
    
    .date-fields {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 15px;
        margin-top: 10px;
    }
    
    .clear-filters {
        color: #dc3545;
        text-decoration: none;
        font-size: 0.875rem;
    }
    
    .clear-filters:hover {
        text-decoration: underline;
    }
    
    .results-count {
        color: #666;
        font-size: 0.9rem;
        margin-bottom: 20px;
    }
    
    .badge-pending { background-color: #ffc107; color: #000; }
    .badge-completed { background-color: #28a745; color: #fff; }
    .badge-rejected { background-color: #dc3545; color: #fff; }
    .badge-successful { background-color: #17a2b8; color: #fff; }
</style>
@endpush

@section('content')
<div class="container py-4">

    {{-- Page Title --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-semibold">My Bookings</h4>
        <span class="results-count">
            {{ $bookings->total() }} {{ Str::plural('booking', $bookings->total()) }}
        </span>
    </div>

    {{-- Minimal Filter Section --}}
    <div class="filter-minimal mb-4">
        
        {{-- Status Tabs --}}
        <div class="status-tabs">
            <div class="d-flex flex-wrap">
                @php
                    $statuses = [
                        'all' => 'All',
                        'pending' => 'Pending',
                        'successful' => 'Successful', 
                        'completed' => 'Completed',
                        'rejected' => 'Rejected'
                    ];
                    $currentStatus = request('status', 'all');
                @endphp
                
                @foreach($statuses as $value => $label)
                    <button type="button" 
                            class="status-tab {{ $currentStatus == $value ? 'active' : '' }}"
                            onclick="setStatus('{{ $value }}')">
                        {{ $label }}
                        @if($value != 'all' && isset($statusCounts[$value]))
                            <span class="badge bg-light text-dark ms-1">
                                {{ $statusCounts[$value] }}
                            </span>
                        @endif
                    </button>
                @endforeach
            </div>
        </div>

        {{-- Search Form --}}
        <form method="GET" action="{{ route('customers.BookingHistory.index') }}" id="filterForm">
            <input type="hidden" name="status" id="statusInput" value="{{ $currentStatus }}">
            
            <div class="row g-3 align-items-end">
                {{-- Booking ID Search --}}
                <div class="col-md-4">
                    <input type="text" 
                           name="search" 
                           class="form-control" 
                           placeholder="Search Booking ID"
                           value="{{ request('search') }}">
                </div>

                {{-- Date Range --}}
                <div class="col-md-5">
                    <div class="row g-2">
                        <div class="col">
                            <input type="date" 
                                   name="from" 
                                   class="form-control" 
                                   placeholder="From date"
                                   value="{{ request('from') }}">
                        </div>
                        <div class="col">
                            <input type="date" 
                                   name="to" 
                                   class="form-control" 
                                   placeholder="To date"
                                   value="{{ request('to') }}">
                        </div>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="col-md-3">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-danger btn-sm flex-grow-1">
                            <i class="fas fa-search me-1"></i> Search
                        </button>
                        
                        @if(request()->hasAny(['from', 'to', 'status', 'search']))
                            <a href="{{ route('customers.BookingHistory.index') }}" 
                               class="btn btn-outline-secondary btn-sm">
                                Clear
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </form>
    </div>

    {{-- Active Filters Indicator --}}
    @if(request()->hasAny(['from', 'to', 'status', 'search']))
    <div class="alert alert-light border mb-4 py-2">
        <small>
            <i class="fas fa-filter text-muted me-1"></i>
            Filters: 
            @if(request('status') && request('status') != 'all')
                <span class="badge bg-danger me-2">Status: {{ ucfirst(request('status')) }}</span>
            @endif
            @if(request('from'))
                <span class="badge bg-light text-dark me-2">From: {{ request('from') }}</span>
            @endif
            @if(request('to'))
                <span class="badge bg-light text-dark me-2">To: {{ request('to') }}</span>
            @endif
            @if(request('search'))
                <span class="badge bg-light text-dark">ID: {{ request('search') }}</span>
            @endif
            <a href="{{ route('customers.BookingHistory.index') }}" class="clear-filters ms-2">
                Clear all
            </a>
        </small>
    </div>
    @endif

    {{-- Bookings Grid --}}
    <div class="row">
        @forelse($bookings as $booking)
            @php
                // Calculate hours with proper rounding
                $hours = \Carbon\Carbon::parse($booking->pickup_dateTime)
                         ->diffInHours(\Carbon\Carbon::parse($booking->return_dateTime), true); // true = float
                $roundedHours = round($hours, 1); // Round to 1 decimal place
            @endphp
            
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        {{-- Booking Header --}}
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <h6 class="fw-bold mb-0">{{ $booking->bookingID }}</h6>
                            @php
                                $badgeClass = match($booking->bookingStatus) {
                                    'pending' => 'badge-pending',
                                    'successful' => 'badge-successful',
                                    'completed' => 'badge-completed',
                                    'rejected' => 'badge-rejected',
                                    default => 'bg-secondary'
                                };
                            @endphp
                            <span class="badge {{ $badgeClass }} px-3 py-1">
                                {{ ucfirst($booking->bookingStatus) }}
                            </span>
                        </div>

                        {{-- Vehicle Info --}}
                        <p class="mb-2">
                            <i class="fas fa-car text-muted me-2"></i>
                            {{ $booking->vehicle->vehicleName ?? 'Vehicle' }}
                        </p>

                        {{-- Dates --}}
                        <div class="mb-3">
                            <small class="text-muted d-block">
                                <i class="far fa-calendar-alt me-2"></i>
                                {{ \Carbon\Carbon::parse($booking->pickup_dateTime)->format('d M Y') }}
                            </small>
                            <small class="text-muted">
                                <i class="far fa-clock me-2"></i>
                                {{ \Carbon\Carbon::parse($booking->pickup_dateTime)->format('h:i A') }}
                                →
                                {{ \Carbon\Carbon::parse($booking->return_dateTime)->format('h:i A') }}
                            </small>
                        </div>

                        {{-- Duration & Stamp --}}
                        <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top">
                            <span class="text-muted small">
                                <i class="fas fa-clock me-1"></i> 
                                @if($roundedHours == round($roundedHours))
                                    {{ (int)$roundedHours }}h
                                @else
                                    {{ $roundedHours }}h
                                @endif
                            </span>
                            
                            @if($hours >= 9 && $booking->bookingStatus == 'completed')
                                <span class="badge bg-warning text-dark small" 
                                      title="Earned loyalty stamp">
                                    <i class="fas fa-star me-1"></i> Stamp
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="card-footer bg-transparent border-top-0 pt-0">
                        <a href="{{ route('customers.BookingHistory.show', $booking) }}"
                           class="btn btn-sm btn-outline-danger w-100">
                            View Details
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="text-center py-5">
                    <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                    <p class="text-muted">No bookings found</p>
                    @if(request()->hasAny(['from', 'to', 'status', 'search']))
                        <a href="{{ route('customers.BookingHistory.index') }}" 
                           class="btn btn-outline-primary btn-sm mt-2">
                            Clear filters
                        </a>
                    @endif
                </div>
            </div>
        @endforelse
    </div>

    {{-- Minimal Pagination --}}
    @if($bookings->hasPages())
    <div class="d-flex justify-content-center mt-5">
        <nav aria-label="Page navigation">
            <ul class="pagination pagination-sm">
                {{-- Previous --}}
                @if($bookings->onFirstPage())
                    <li class="page-item disabled">
                        <span class="page-link">‹</span>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link" href="{{ $bookings->previousPageUrl() }}">‹</a>
                    </li>
                @endif

                {{-- Pages --}}
                @php
                    $current = $bookings->currentPage();
                    $last = $bookings->lastPage();
                    $start = max($current - 1, 1);
                    $end = min($current + 1, $last);
                @endphp

                @if($start > 1)
                    <li class="page-item">
                        <a class="page-link" href="{{ $bookings->url(1) }}">1</a>
                    </li>
                    @if($start > 2)
                        <li class="page-item disabled"><span class="page-link">...</span></li>
                    @endif
                @endif

                @for($i = $start; $i <= $end; $i++)
                    <li class="page-item {{ $i == $current ? 'active' : '' }}">
                        <a class="page-link" href="{{ $bookings->url($i) }}">{{ $i }}</a>
                    </li>
                @endfor

                @if($end < $last)
                    @if($end < $last - 1)
                        <li class="page-item disabled"><span class="page-link">...</span></li>
                    @endif
                    <li class="page-item">
                        <a class="page-link" href="{{ $bookings->url($last) }}">{{ $last }}</a>
                    </li>
                @endif

                {{-- Next --}}
                @if($bookings->hasMorePages())
                    <li class="page-item">
                        <a class="page-link" href="{{ $bookings->nextPageUrl() }}">›</a>
                    </li>
                @else
                    <li class="page-item disabled">
                        <span class="page-link">›</span>
                    </li>
                @endif
            </ul>
        </nav>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
function setStatus(status) {
    document.getElementById('statusInput').value = status;
    document.getElementById('filterForm').submit();
}
</script>
@endpush