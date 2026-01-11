@extends('layouts.it_admin')

@section('title', 'Commission Verification')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">Commission Verification</h4>
            <p class="text-muted mb-0">Approve or reject commission applications</p>
        </div>
        <div class="btn-group">
            <a href="{{ route('admin.commissionVerify.index') }}" class="btn btn-outline-primary">
                All
            </a>
            <a href="{{ route('admin.commissionVerify.index') }}?status=pending" class="btn btn-outline-warning">
                Pending
            </a>
            <a href="{{ route('admin.commissionVerify.index') }}?status=approved" class="btn btn-outline-success">
                Approved
            </a>
            <a href="{{ route('admin.commissionVerify.index') }}?status=rejected" class="btn btn-outline-danger">
                Rejected
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="card shadow">
        <div class="card-body table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Commission ID</th>
                        <th>Staff</th>
                        <th>Type</th>
                        <th>Bank Details</th>
                        <th>Status</th>
                        <th>Applied Date</th>
                        <th>Amount (RM)</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($commissions as $commission)
                        <tr>
                            <td>
                                <strong>{{ $commission->commissionID }}</strong>
                            </td>
                            <td>
                                {{ $commission->user->name ?? 'N/A' }}<br>
                                <small class="text-muted">{{ $commission->userID ?? 'No User ID' }}</small>
                            </td>
                            <td>{{ $commission->commissionType ?? 'N/A' }}</td>
                            <td>
                                @if($commission->bankName && $commission->accountNumber)
                                    <div><strong>{{ $commission->bankName }}</strong></div>
                                    <div class="text-muted small">{{ $commission->accountNumber }}</div>
                                    <div class="text-muted small">{{ ucfirst($commission->bankType ?? '') }} Account</div>
                                @else
                                    <span class="text-muted">Not provided</span>
                                @endif
                            </td>
                            <td>
                                @if ($commission->status === 'approved')
                                    <span class="badge bg-success">Approved</span>
                                @elseif ($commission->status === 'rejected')
                                    <span class="badge bg-danger">Rejected</span>
                                @elseif ($commission->status === 'pending')
                                    <span class="badge bg-warning">Pending</span>
                                @else
                                    <span class="badge bg-secondary">No Status</span>
                                @endif
                            </td>
                            <td>{{ $commission->appliedDate ? \Carbon\Carbon::parse($commission->appliedDate)->format('d M Y') : 'N/A' }}</td>
                            <td>{{ $commission->amount ? number_format($commission->amount, 2) : '0.00' }}</td>
                            <td>
                                <a href="{{ route('admin.commissionVerify.show', $commission->commissionID) }}" 
                                   class="btn btn-sm btn-info mb-1">
                                   View Details
                                </a>
                                
                                @if(!$commission->status || $commission->status === 'pending')
                                    <form action="{{ route('admin.commissionVerify.approve', $commission->commissionID) }}" 
                                          method="POST" class="d-inline"
                                          onsubmit="return confirm('Are you sure you want to APPROVE this commission?')">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success mb-1">
                                            Approve
                                        </button>
                                    </form>
                                    
                                    <form action="{{ route('admin.commissionVerify.reject', $commission->commissionID) }}" 
                                          method="POST" class="d-inline"
                                          onsubmit="return confirm('Are you sure you want to REJECT this commission?')">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-danger mb-1">
                                            Reject
                                        </button>
                                    </form>
                                @else
                                    <span class="text-muted">Processed</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">No commission records found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
// Debug script untuk form submission
document.addEventListener('DOMContentLoaded', function() {
    // Log semua form submissions
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function(e) {
            console.log('Form submitted:', this.action);
            console.log('Method:', this.method);
            console.log('CSRF token:', this.querySelector('input[name="_token"]')?.value);
        });
    });
    
    // Log semua button clicks
    document.querySelectorAll('button[type="submit"]').forEach(button => {
        button.addEventListener('click', function(e) {
            console.log('Button clicked:', this.textContent.trim());
            console.log('Form action:', this.closest('form').action);
        });
    });
});
</script>
@endsection