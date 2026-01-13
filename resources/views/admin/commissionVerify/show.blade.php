@extends('layouts.it_admin')

@section('title', 'Commission Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Commission Details</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Commission Information</h6>
                            <table class="table table-bordered">
                                <tr>
                                    <th width="40%">Commission ID:</th>
                                    <td><strong>{{ $commission->commissionID ?? 'N/A' }}</strong></td>
                                </tr>
                                <tr>
                                    <th>Type:</th>
                                    <td>{{ $commission->commissionType ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Applied Date:</th>
                                    <td>{{ $commission->appliedDate ? \Carbon\Carbon::parse($commission->appliedDate)->format('d M Y') : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Amount:</th>
                                    <td class="fw-bold">RM {{ $commission->amount ? number_format($commission->amount, 2) : '0.00' }}</td>
                                </tr>
                                <tr>
                                    <th>Status:</th>
                                    <td>
                                        @if ($commission->status === 'approved')
                                            <span class="badge bg-success">Approved</span>
                                        @elseif ($commission->status === 'rejected')
                                            <span class="badge bg-danger">Rejected</span>
                                        @else
                                            <span class="badge bg-warning">Pending</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                        
                        <div class="col-md-6">
                            <h6>Staff Information</h6>
                            <table class="table table-bordered">
                                <tr>
                                    <th width="40%">Staff ID:</th>
                                    <td>{{ $commission->userID ?? 'No User ID' }}</td>
                                </tr>
                                <tr>
                                    <th>Staff Name:</th>
                                    <td>{{ $commission->user->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Email:</th>
                                    <td>{{ $commission->user->email ?? 'N/A' }}</td>
                                </tr>
                            </table>
                            
                            <h6 class="mt-4">Bank Details</h6>
                            <table class="table table-bordered">
                                <tr>
                                    <th width="40%">Bank Name:</th>
                                    <td>{{ $commission->bankName ?? 'Not provided' }}</td>
                                </tr>
                                <tr>
                                    <th>Account Number:</th>
                                    <td>{{ $commission->accountNumber ?? 'Not provided' }}</td>
                                </tr>
                                <tr>
                                    <th>Account Type:</th>
                                    <td>{{ $commission->bankType ? ucfirst($commission->bankType) : 'Not provided' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="mt-4">
    @if($commission->status === 'pending')
        <form action="{{ route('admin.commissionVerify.approve', $commission->commissionID) }}" 
              method="POST" class="d-inline"
              onsubmit="return confirm('Are you sure you want to APPROVE this commission?')">
            @csrf
            <button type="submit" class="btn btn-success" style="padding: 0.375rem 0.75rem; font-size: 0.875rem;">
                <i class="fas fa-check-circle"></i> Approve Commission
            </button>
        </form>
        
        <form action="{{ route('admin.commissionVerify.reject', $commission->commissionID) }}" 
              method="POST" class="d-inline"
              onsubmit="return confirm('Are you sure you want to REJECT this commission?')">
            @csrf
            <button type="submit" class="btn btn-danger ms-2" style="padding: 0.375rem 0.75rem; font-size: 0.875rem;">
                <i class="fas fa-times-circle"></i> Reject Commission
            </button>
        </form>
    @else
        <div class="alert alert-info" style="padding: 0.375rem 0.75rem; margin-bottom: 0.5rem;">
            This commission has already been {{ $commission->status ?? 'processed' }}.
        </div>
    @endif
    
    <a href="{{ route('admin.commissionVerify.index') }}" class="btn btn-secondary ms-2" style="padding: 0.375rem 0.75rem; font-size: 0.875rem;">
        <i class="fas fa-arrow-left"></i> Back to List
    </a>
</div>
                </div>
            </div>
        </div>
        
        <!-- Receipt/Proof Section -->
        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Receipt/Proof Section</h5>
                </div>
                <div class="card-body text-center">
                    @if(!empty($commission->receipt_file_path))
                        <img src="{{ asset('storage/' . $commission->receipt_file_path) }}" 
                             alt="Receipt" class="img-fluid mb-3" style="max-height: 300px;">
                        <a href="{{ asset('storage/' . $commission->receipt_file_path) }}" 
                            target="_blank" class="btn btn-primary px-3 py-1">
                            <i class="fas fa-download"></i> View Receipt
                        </a>
                    @else
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            No receipt/proof uploaded for this commission.
                        </div>
                        <p class="text-muted">Staff has not uploaded any supporting document.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- FontAwesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endsection