@extends('layouts.salesperson')

@section('title', 'Damage Cases Management')

@section('content')
<div class="container-fluid">
    <!-- Header with Stats -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0"><i class="fas fa-car-crash"></i> Damage Cases</h2>
            <p class="text-muted mb-0">Manage all vehicle damage reports and cases</p>
        </div>
        <div>
            <a href="{{ route('staff.damage-cases.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> New Damage Case
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3 col-sm-6">
            <div class="card border-start border-primary border-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-muted">Total Cases</h6>
                            <h3 class="mb-0">{{ $stats['total'] }}</h3>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-list text-primary fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card border-start border-warning border-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-muted">Open Cases</h6>
                            <h3 class="mb-0">{{ $stats['open'] }}</h3>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-clock text-warning fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card border-start border-danger border-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-muted">Overdue</h6>
                            <h3 class="mb-0">{{ $stats['overdue'] }}</h3>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-exclamation-triangle text-danger fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card border-start border-success border-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-muted">Total Cost</h6>
                            <h3 class="mb-0">RM {{ number_format($stats['total_cost'], 2) }}</h3>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-money-bill-wave text-success fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters Card -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-filter"></i> Filters</h5>
        </div>
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        @foreach($statuses as $status)
                        <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                            {{ $status }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Severity</label>
                    <select name="severity" class="form-select">
                        <option value="">All Severity</option>
                        @foreach($severities as $severity)
                        <option value="{{ $severity }}" {{ request('severity') == $severity ? 'selected' : '' }}>
                            {{ $severity }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Case Type</label>
                    <select name="casetype" class="form-select">
                        <option value="">All Types</option>
                        @foreach($caseTypes as $type)
                        <option value="{{ $type }}" {{ request('casetype') == $type ? 'selected' : '' }}>
                            {{ $type }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Assigned To</label>
                    <select name="assigned_to" class="form-select">
                        <option value="">All Staff</option>
                        @foreach($staffUsers as $user)
                        <option value="{{ $user->userID }}" {{ request('assigned_to') == $user->userID ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Search</label>
                    <input type="text" name="search" class="form-control" 
                           placeholder="Search by Case ID, Vehicle, Description..." 
                           value="{{ request('search') }}">
                </div>
                <div class="col-md-6">
                    <div class="d-flex align-items-end h-100 gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Apply Filters
                        </button>
                        <a href="{{ route('staff.damage-cases.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-redo"></i> Reset
                        </a>
                        <div class="form-check ms-3">
                            <input type="checkbox" name="overdue" value="1" class="form-check-input" 
                                   id="overdue" {{ request('overdue') ? 'checked' : '' }}>
                            <label class="form-check-label" for="overdue">
                                Show Overdue Only
                            </label>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Cases Table -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Damage Cases List</h5>
            <div class="d-flex gap-2">
                <button class="btn btn-outline-primary btn-sm" id="exportBtn">
                    <i class="fas fa-download"></i> Export
                </button>
                <button class="btn btn-outline-secondary btn-sm" onclick="window.print()">
                    <i class="fas fa-print"></i> Print
                </button>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="80">Case ID</th>
                            <th>Vehicle</th>
                            <th>Damage Type</th>
                            <th>Severity</th>
                            <th>Assigned To</th>
                            <th>Status</th>
                            <th>Estimated Cost</th>
                            <th>Due Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($damageCases as $case)
                        <tr class="{{ $case->isOverdue() ? 'table-warning' : '' }}">
                            <td>
                                <strong>#{{ $case->caseID }}</strong>
                                @if($case->isOverdue())
                                <span class="badge bg-danger ms-1">Overdue</span>
                                @endif
                            </td>
                            <td>
                                @if($case->inspection && $case->inspection->vehicle)
                                <div>
                                    <strong>{{ $case->inspection->vehicle->plate_number }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $case->inspection->vehicle->model }}</small>
                                </div>
                                @else
                                <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>{{ $case->casetype }}</td>
                            <td>
                                <span class="badge bg-{{ $case->severity_color }}">
                                    {{ $case->severity }}
                                </span>
                            </td>
                            <td>
                                @if($case->assignedUser)
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm me-2">
                                        <div class="avatar-title bg-light rounded-circle">
                                            {{ substr($case->assignedUser->name, 0, 1) }}
                                        </div>
                                    </div>
                                    <div>{{ $case->assignedUser->name }}</div>
                                </div>
                                @else
                                <span class="text-muted">Unassigned</span>
                                @endif
                            </td>
                            <td>
                                <select class="form-select form-select-sm status-select" 
                                        data-case-id="{{ $case->caseID }}"
                                        style="width: 120px;">
                                    @foreach($statuses as $status)
                                    <option value="{{ $status }}" 
                                            {{ $case->resolutionstatus == $status ? 'selected' : '' }}>
                                        {{ $status }}
                                    </option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <strong class="text-primary">
                                    RM {{ number_format($case->estimated_cost, 2) }}
                                </strong>
                            </td>
                            <td>
                                @if($case->due_date)
                                <span class="{{ $case->isOverdue() ? 'text-danger' : '' }}">
                                    {{ $case->due_date->format('d/m/Y') }}
                                </span>
                                @else
                                <span class="text-muted">Not set</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('staff.damage-cases.show', $case) }}" 
                                       class="btn btn-info" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('staff.damage-cases.edit', $case) }}" 
                                       class="btn btn-warning" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if($case->hasMedia('damage_photos'))
                                    <button class="btn btn-success view-photos-btn" 
                                            data-case-id="{{ $case->caseID }}"
                                            title="View Photos">
                                        <i class="fas fa-camera"></i>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-4">
                                <i class="fas fa-inbox fa-2x text-muted mb-3"></i>
                                <h5>No damage cases found</h5>
                                <p class="text-muted">Try adjusting your filters or create a new case</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($damageCases->hasPages())
            <div class="card-footer">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        Showing {{ $damageCases->firstItem() }} to {{ $damageCases->lastItem() }} 
                        of {{ $damageCases->total() }} entries
                    </div>
                    <div>
                        {{ $damageCases->withQueryString()->links() }}
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Photos Modal -->
<div class="modal fade" id="photosModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Damage Photos</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="photosContainer" class="row">
                    <!-- Photos will be loaded here -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Quick status update
    $('.status-select').change(function() {
        const caseId = $(this).data('case-id');
        const newStatus = $(this).val();
        
        $.ajax({
            url: '/staff/damage-cases/' + caseId + '/update-status',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                resolutionstatus: newStatus
            },
            success: function(response) {
                toastr.success(response.message);
                // Update row color if needed
                if (newStatus === 'Resolved' || newStatus === 'Closed') {
                    $(this).closest('tr').removeClass('table-warning');
                }
            },
            error: function() {
                toastr.error('Failed to update status');
            }
        });
    });

    // View photos
    $('.view-photos-btn').click(function() {
        const caseId = $(this).data('case-id');
        
        $.ajax({
            url: '/staff/damage-cases/' + caseId + '/photos',
            method: 'GET',
            success: function(response) {
                $('#photosContainer').html(response);
                $('#photosModal').modal('show');
            }
        });
    });

    // Export functionality
    $('#exportBtn').click(function() {
        const filters = {
            status: $('[name="status"]').val(),
            severity: $('[name="severity"]').val(),
            search: $('[name="search"]').val()
        };
        
        const queryString = new URLSearchParams(filters).toString();
        window.location.href = '/staff/damage-cases/export?' + queryString;
    });
});
</script>
@endpush