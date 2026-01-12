@extends('layouts.customer')

@section('title', 'My Profile')

@push('styles')
<style>
    .profile-header {
        background: linear-gradient(135deg, var(--customer-primary), var(--customer-secondary));
        color: white;
        border-radius: 14px;
        padding: 25px;
    }

    .profile-avatar-lg {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: white;
        color: var(--customer-primary);
        font-size: 32px;
        font-weight: bold;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .card {
        border: none;
        border-radius: 14px;
        box-shadow: 0 6px 18px rgba(0,0,0,0.06);
    }

    .card-header {
        background: transparent;
        font-weight: 600;
        border-bottom: 1px solid #f1f1f1;
    }

    .stat-card {
        border-radius: 14px;
        padding: 20px;
        background: #fff;
        text-align: center;
        box-shadow: 0 6px 18px rgba(0,0,0,0.06);
    }

    .stat-icon {
        font-size: 28px;
        color: var(--customer-primary);
        margin-bottom: 8px;
    }

    .table td, .table th {
        vertical-align: middle;
    }
</style>
@endpush

@section('content')
<div class="container">

    {{-- PROFILE HEADER --}}
    <div class="profile-header mb-4 d-flex align-items-center gap-4">
        <div class="profile-avatar-lg">
            {{ strtoupper(substr($user->name,0,1)) }}
        </div>
        <div>
            <h4 class="mb-1">{{ $user->name }}</h4>
            <div class="small">{{ $user->email }}</div>
            <span class="badge 
            @if($customer->customerStatus == 'active') bg-success
            @elseif($customer->customerStatus == 'blacklisted') bg-dark
            @else bg-warning @endif">
                {{ ucfirst($customer->customerStatus) }}
            </span>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('customer.profile.update') }}" method="POST">
        @csrf
        @method('PUT')

        {{-- PERSONAL INFO --}}
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-user me-2"></i> Personal Information
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="name"
                            class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name', $user->name) }}">
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Phone Number</label>
                        <input type="text" name="noHP"
                            class="form-control @error('noHP') is-invalid @enderror"
                            value="{{ old('noHP', $user->noHP) }}">
                        @error('noHP') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input class="form-control" disabled value="{{ $user->email }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">IC Number</label>
                        <input class="form-control" disabled value="{{ $user->noIC }}">
                    </div>

                    <!-- Customer Type Specific Fields -->
                     @if($customer->customerType == 'student' && $customer->studentCustomer)
                        <div class="col-md-6">
                            <label class="form-label">Matric No.</label>
                            <input class="form-control" disabled value="{{ $customer->studentCustomer->matricNo }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Faculty</label>
                            <input class="form-control" disabled value="{{ $customer->studentCustomer->faculty->facultyName }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">College</label>
                            <select name="collegeID" 
                                class="form-select @error('collegeID') is-invalid @enderror"
                                value="{{ old('college', $customer->studentCustomer->college->collegeName) }}">
                                @foreach($colleges as $college)
                                    <option value="{{ $college->collegeID }}"
                                        {{ $college->collegeID == $customer->studentCustomer->collegeID ? 'selected' : '' }}>
                                        {{ $college->collegeName }}
                                    </option>
                                @endforeach
                            </select>
                            @error('collegeID')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        </div>
                    @elseif($customer->customerType == 'staff' && $customer->staffCustomer)
                        <div class="col-md-6">
                            <label class="form-label">Staff No.</label>
                            <input class="form-control" disabled value="{{ $customer->staffCustomer->staffNo }}">
                        </div>
                    @endif

                </div>
            </div>
        </div>

        {{-- BANK INFO --}}
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-university me-2"></i> Bank Details
            </div>
            <div class="card-body row g-3">
                <div class="col-md-6">
                    <label class="form-label">Account Number</label>
                    <input type="text" name="accountNumber"
                        class="form-control"
                        value="{{ old('accountNumber', $customer->accountNumber) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Bank</label>
                    <input type="text" name="bankType"
                        class="form-control"
                        value="{{ old('bankType', $customer->bankType) }}">
                </div>
            </div>
        </div>

        <div class="text-end mb-4">
            <button class="btn btn-danger px-4">
                <i class="fas fa-save me-1"></i> Update Profile
            </button>
        </div>
    </form>

    {{-- STATS --}}
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-stamp"></i></div>
                <h5>{{ $customer->loyaltyCard->currentStamp ?? 0 }}</h5>
                <small>Current Stamps</small>
            </div>
        </div>

        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-users"></i></div>
                <h5>{{ $customer->referral_count ?? 0 }}</h5>
                <small>Total Referrals</small>
            </div>
        </div>

        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-ticket-alt"></i></div>
                <h5>{{ $customer->vouchers->count() }}</h5>
                <small>Vouchers</small>
            </div>
        </div>
    </div>

    {{-- VOUCHERS --}}
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-gift me-2"></i> My Vouchers
        </div>
        <div class="card-body">
            <p><strong>Referral Code:</strong> {{ $customer->loyaltyCard->referralCode }}</p>
            @if($customer->vouchers->count())
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Voucher Code</th>
                                <th>Type</th>
                                <th>Details</th>
                                <th>Status</th>
                                <th>Expiry</th>
                                <th>Redeemed At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($customer->vouchers as $voucher)
                                @php
                                    $display = \App\Services\ReferralService::getVoucherDisplayDetails($voucher->voucherCode);
                                @endphp
                                <tr>
                                    <td><strong>{{ $voucher->voucherCode }}</strong></td>
                                    <td>{{ ucfirst($voucher->type) }}</td>
                                    <td>
                                            <strong>{{ $display['name'] }}</strong><br>
                                            <small>{{ $display['vendor'] }} - {{ $display['benefit'] }}</small>
                                    </td>
                                    <td>
                                        <span class="badge 
                                                @if($voucher->status == 'active' && !$voucher->pivot->redeemed_at) bg-success
                                                @elseif($voucher->pivot->redeemed_at) bg-secondary
                                                @elseif($voucher->status == 'expired') bg-dark
                                                @else bg-warning @endif
                                                text-white">
                                                @if($voucher->pivot->redeemed_at)
                                                    Used
                                                @else
                                                    {{ ucfirst($voucher->status) }}
                                                @endif
                                        </span>
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($voucher->expiryDate)->format('d M Y') }}</td>
                                    <td>
                                            @if($voucher->pivot->redeemed_at)
                                                {{ \Carbon\Carbon::parse($voucher->pivot->redeemed_at)->format('d M Y H:i') }}
                                            @else
                                                <span class="text-muted">Not redeemed</span>
                                            @endif
                                        </td>
                                        <td>
                                        @if($voucher->status == 'active' && !$voucher->pivot->redeemed_at)
                                            <!-- ACTIVE VOUCHER - CAN BE REDEEMED -->
                                            <form action="/customer/voucher/{{ $voucher->voucherCode }}/redeem" 
                                                method="POST" 
                                                class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-success px-3">
                                                    <i class="fas fa-check-circle me-1"></i> Mark as Used
                                                </button>
                                            </form>
                                            
                                            <!-- Success message will appear after redemption -->
                                            @if(session('success') && str_contains(session('success'), $voucher->voucherCode))
                                                <div class="text-success small mt-1">
                                                    <i class="fas fa-check"></i> {{ session('success') }}
                                                </div>
                                            @endif
                                            
                                        @elseif($voucher->pivot->redeemed_at)
                                            <span class="badge bg-info text-dark px-3 py-2">
                                                <i class="fas fa-check me-1"></i> Used
                                            </span>

                                        @elseif($voucher->status == 'expired')
                                            <span class="badge bg-dark px-3 py-2">
                                                Expired
                                            </span>
                                        @else
                                            <span class="badge bg-warning">{{ ucfirst($voucher->status) }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-4 text-muted">
                    <i class="fas fa-ticket-alt fa-3x mb-3"></i>
                    <h5>No vouchers yet</h5>
                    <p class="text-muted">You haven't earned any vouchers yet.</p>
                    <p>Refer friends to earn vouchers!</p>
                </div>
            @endif
        </div>
    </div>

</div>
@endsection
