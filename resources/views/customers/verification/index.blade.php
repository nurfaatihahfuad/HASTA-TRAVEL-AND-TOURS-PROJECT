<!-- resources/views/sales/verification/index.blade.php -->
@extends('layouts.sales')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            Customer Verifications
            <span class="badge badge-warning">{{ $pendingCount }} pending</span>
        </h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Customer</th>
                            <th>Contact</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Submitted</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pendingUsers as $user)
                            <tr>
                                <td>
                                    <strong>{{ $user->name }}</strong><br>
                                    <small class="text-muted">ID: {{ $user->userID }}</small>
                                </td>
                                <td>
                                    {{ $user->email }}<br>
                                    <small>{{ $user->phone }}</small>
                                </td>
                                <td>
                                    @if($user->customer)
                                        <span class="badge badge-info">
                                            {{ ucfirst($user->customer->customerType) }}
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if($user->verificationDocs)
                                        <span class="badge badge-warning">
                                            {{ ucfirst($user->verificationDocs->status) }}
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    {{ $user->created_at->format('M d, Y') }}
                                </td>
                                <td>
                                    <a href="{{ route('sales.verification.show', $user->userID) }}" 
                                       class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye"></i> Review
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    <i class="fas fa-check-circle fa-2x mb-2"></i><br>
                                    No pending verifications
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection