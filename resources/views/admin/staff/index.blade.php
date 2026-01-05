<!--List of staff for CRUD-->
@extends('layouts.it_admin')

@section('title', 'Staff Management')

@section('content')
<!-- Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1">Staff Management</h4>
        <p class="text-muted mb-0">Manage all staff members</p>
    </div>
    <a href="{{ route('staff.create') }}" class="btn btn-primary">
        <i class="fas fa-user-plus me-2"></i> Add New Staff
    </a>
</div>

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-md-4 mb-3">
        <div class="card border-0 bg-primary bg-opacity-10">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="bg-primary bg-opacity-25 rounded-circle p-3 me-3">
                        <i class="fas fa-users text-primary"></i>
                    </div>
                    <div>
                        <h5 class="card-title mb-0">{{ $totalStaff}}</h5>
                        <p class="text-muted mb-0">Total Staff</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-3">
        <div class="card border-0 bg-info bg-opacity-10">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="bg-info bg-opacity-25 rounded-circle p-3 me-3">
                        <i class="fas fa-chart-line text-info"></i>
                    </div>
                    <div>
                        <h5 class="card-title mb-0">{{ $totalSalespersons }}</h5>
                        <p class="text-muted mb-0">Salespersons</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-3">
        <div class="card border-0 bg-success bg-opacity-10">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="bg-success bg-opacity-25 rounded-circle p-3 me-3">
                        <i class="fas fa-truck text-success"></i>
                    </div>
                    <div>
                        <h5 class="card-title mb-0">{{ $totalRunners }}</h5>
                        <p class="text-muted mb-0">Runners</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Staff Table -->
<div class="section-card">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Filter Buttons -->
    <div class="d-flex justify-content-center mb-4">
        <div class="btn-group" role="group">
            <a href="{{ route('staff.index') }}" 
               class="btn {{ !request('role') ? 'btn-primary' : 'btn-outline-primary' }}">
                All Staff
            </a>
            <a href="{{ route('staff.index', ['role' => 'salesperson']) }}" 
               class="btn {{ request('role') == 'salesperson' ? 'btn-primary' : 'btn-outline-primary' }}">
                Salespersons
            </a>
            <a href="{{ route('staff.index', ['role' => 'runner']) }}" 
               class="btn {{ request('role') == 'runner' ? 'btn-primary' : 'btn-outline-primary' }}">
                Runners
            </a>
        </div>
    </div>

    <!-- Table -->
    <div class="table-responsive">
        <table class="table table-hover">
            <thead class="table-light">
                <tr>
                    <th>Staff ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Phone</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($staff as $member)
                    <tr>
                        <td>
                            <span class="fw-bold">{{ $member->userID }}</span>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle bg-secondary bg-opacity-10 p-2 me-3">
                                    <i class="fas fa-user text-secondary"></i>
                                </div>
                                <div>
                                    <div class="fw-medium">{{ $member->name }}</div>
                                </div>
                            </div>
                        </td>
                        <td>{{ $member->email }}</td>
                        <td>
                            @if($member->staff)
                                @if($member->staff->staffRole == 'salesperson')
                                    <span class="badge bg-info">
                                        <i class="fas fa-chart-line me-1"></i> Salesperson
                                    </span>
                                @else
                                    <span class="badge bg-success">
                                        <i class="fas fa-truck me-1"></i> Runner
                                    </span>
                                @endif
                            @else
                                <span class="badge bg-warning">
                                    <i class="fas fa-question-circle me-1"></i> No Role
                                </span>
                            @endif
                        </td>
                        <td>+60 {{ $member->noHP }}</td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('staff.show', $member->userID) }}" 
                                   class="btn btn-outline-info" 
                                   title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('staff.edit', $member->userID) }}" 
                                   class="btn btn-outline-primary" 
                                   title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" 
                                        class="btn btn-outline-danger" 
                                        title="Delete"
                                        data-bs-toggle="modal" 
                                        data-bs-target="#deleteModal{{ $member->userID }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                            
                            <!-- Delete Modal -->
                            <div class="modal fade" id="deleteModal{{ $member->userID }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Confirm Delete</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Are you sure you want to delete staff member <strong>{{ $member->name }}</strong> ({{ $member->userID }})?</p>
                                            <p class="text-danger mb-0">This action cannot be undone.</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <form action="{{ route('staff.destroy', $member->userID) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">Delete</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4">
                            <div class="text-muted">
                                <i class="fas fa-users fa-2x mb-3"></i>
                                <p class="mb-0">No staff members found</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
</div>
@endsection