<!--List of admins for CRUD-->
@extends('layouts.it_admin')

@section('title', 'Admin Management')

@section('content')
<!-- Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1">Admin Management</h4>
        <p class="text-muted mb-0">Manage system administrators</p>
    </div>
    <a href="{{ route('admins.create') }}" class="btn btn-primary">
        <i class="fas fa-user-plus me-2"></i> Add New Admin
    </a>
</div>


<!-- Stats Card -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card border-0 bg-primary bg-opacity-10">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="bg-primary bg-opacity-25 rounded-circle p-3 me-3">
                        <i class="fas fa-user-shield text-primary"></i>
                    </div>
                    <div>
                        <h5 class="card-title mb-0">{{ $totalAdmin}}</h5>
                        <p class="text-muted mb-0">Total Admins</p>
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
                        <i class="fa fa-cogs"></i>
                    </div>
                    <div>
                        <h5 class="card-title mb-0">{{ $totalITAdmin }}</h5>
                        <p class="text-muted mb-0">IT Department</p>
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
                        <i class='fas fa-donate'></i>
                    </div>
                    <div>
                        <h5 class="card-title mb-0">{{ $totalfinanceAd }}</h5>
                        <p class="text-muted mb-0">Finance Department</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Admins Table -->
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
            <a href="{{ route('admins.index') }}" 
               class="btn {{ !request('role') ? 'btn-primary' : 'btn-outline-primary' }}">
                All Admin
            </a>
            <a href="{{ route('admins.index', ['role' => 'IT']) }}" 
               class="btn {{ request('role') == 'IT' ? 'btn-primary' : 'btn-outline-primary' }}">
                IT Admin
            </a>
            <a href="{{ route('admins.index', ['role' => 'finance']) }}" 
               class="btn {{ request('role') == 'finance' ? 'btn-primary' : 'btn-outline-primary' }}">
                Finance Admin
            </a>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover">
            <thead class="table-light">
                <tr>
                    <th>Admin ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Department</th>
                    <th>Phone</th>
                    <th>IC Number</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($admin as $member)
                    <tr>
                        <td>
                            <span class="fw-bold">{{ $member->userID }}</span>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle bg-primary bg-opacity-10 p-2 me-3">
                                    <i class="fas fa-user-shield text-primary"></i>
                                </div>
                                <div>
                                    <div class="fw-medium">{{ $member->name }}</div>
                                </div>
                            </div>
                        </td>
                        <td>{{ $member->email }}</td>
                        <td>
                            
                            @if($member->admin)
                                @if($member->admin->adminType == 'IT')
                                    <span class="badge bg-info">
                                        <i class="fa fa-cogs"></i> IT Admin
                                    </span>
                                @else
                                    <span class="badge bg-success">
                                        <i class='fas fa-donate'></i> Finance Admin
                                    </span>
                                @endif
                            @else
                                <span class="badge bg-warning">
                                    <i class="fas fa-question-circle me-1"></i> No Role
                                </span>
                            @endif
                        </td>
                        <td>+60 {{ $member->noHP }}</td>
                        <td>{{ $member->noIC }}</td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admins.show', $member->userID) }}" 
                                   class="btn btn-outline-info" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admins.edit', $member->userID) }}" 
                                   class="btn btn-outline-primary" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" 
                                        class="btn btn-outline-danger" 
                                        title="Delete"
                                        data-bs-toggle="modal" 
                                        data-bs-target="#deleteModal{{ $member->userID }}"
                                        {{ $member->userID === auth()->id() ? 'disabled' : '' }}>
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
                                            <p>Are you sure you want to delete admin <strong>{{ $member->name }}</strong> ({{ $member->userID }})?</p>
                                            <p class="text-danger mb-0">This action cannot be undone.</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <form action="{{ route('admins.destroy', $member->userID) }}" method="POST">
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
                                <i class="fas fa-user-shield fa-2x mb-3"></i>
                                <p class="mb-0">No administrators found</p>
                                <a href="{{ route('admins.create') }}" class="btn btn-primary mt-3">
                                    <i class="fas fa-user-plus me-2"></i> Add First Admin
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection