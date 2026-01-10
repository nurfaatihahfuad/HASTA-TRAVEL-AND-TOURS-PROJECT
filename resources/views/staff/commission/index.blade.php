@extends('layouts.salesperson')

@section('title', 'Commission')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">Commission Records</h4>
            <p class="text-muted mb-0">List of all commission applications</p>
        </div>
        <a href="{{ route('commission.create') }}" class="btn btn-success">
            + Add Commission
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card shadow">
        <div class="card-body table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Commission ID</th>
                        <th>Type</th>
                        <th>Bank Account</th>
                        <th>Bank Name</th>
                        <th>Applied Date</th>
                        <th>Amount (RM)</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($commissions as $commission)
                        <tr>
                            <td>{{ $commission->commissionID }}</td>
                            <td>{{ $commission->commissionType }}</td>
                            <td>
                                @if($commission->accountNumber)
                                    {{ $commission->accountNumber }}
                                @else
                                    <span class="text-muted">Not provided</span>
                                @endif
                            </td>
                            <td>
                                @if($commission->bankName)
                                    {{ $commission->bankName }}
                                @else
                                    <span class="text-muted">Not provided</span>
                                @endif
                            </td>
                            <td>{{ \Carbon\Carbon::parse($commission->appliedDate)->format('d M Y') }}</td>
                            <td>{{ number_format($commission->amount, 2) }}</td>
                            <td>
                                @if ($commission->status === 'approved')
                                    <span class="badge bg-success">Approved</span>
                                @elseif ($commission->status === 'rejected')
                                    <span class="badge bg-danger">Rejected</span>
                                @else
                                    <span class="badge bg-warning">Pending</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('commission.edit', $commission->commissionID) }}" 
                                   class="btn btn-sm btn-primary">Edit</a>
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
@endsection