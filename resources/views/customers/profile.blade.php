@extends('layouts.customer')

@section('content')
<div class="container">
    <h2 class="mb-4">My Profile</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('customer.profile.update') }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Basic Info -->
        <div class="card mb-4">
            <div class="card-header">Personal Information</div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="name" class="form-label">Full Name</label>
                    <input type="text" name="name" id="name"
                           class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name', $user->name) }}">
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" value="{{ $user->email }}" disabled>
                    <small class="text-muted">Email cannot be changed</small>
                </div>

                <div class="mb-3">
                    <label for="noIC" class="form-label">IC Number</label>
                    <input type="text" class="form-control" value="{{ $user->noIC }}" disabled>
                </div>

                <div class="mb-3">
                    <label for="noHP" class="form-label">Phone Number</label>
                    <input type="text" name="noHP" id="noHP"
                           class="form-control @error('noHP') is-invalid @enderror"
                           value="{{ old('noHP', $user->noHP ?? '') }}">
                    @error('noHP') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <!-- Customer Type Specific Info -->
                @if($customer->customerType == 'student' && $customer->studentCustomer)
                    <div class="mb-3">
                        <label class="form-label">Student Details</label>
                        <p class="mb-1">Matric No: {{ $customer->studentCustomer->matricNo }}</p>
                        <p class="mb-1">Faculty: {{ $customer->studentCustomer->faculty->facultyName ?? 'N/A' }}</p>
                        <p class="mb-0">College: {{ $customer->studentCustomer->college->collegeName ?? 'N/A' }}</p>
                    </div>
                @elseif($customer->customerType == 'staff' && $customer->staffCustomer)
                    <div class="mb-3">
                        <label class="form-label">Staff Details</label>
                        <p class="mb-0">Staff No: {{ $customer->staffCustomer->staffNo }}</p>
                    </div>
                @endif

                <div class="mb-3">
                    <label for="accountNumber" class="form-label">Account Number</label>
                    <input type="text" name="accountNumber" id="accountNumber"
                           class="form-control @error('accountNumber') is-invalid @enderror"
                           value="{{ old('accountNumber', $customer->accountNumber ?? '') }}">
                    @error('accountNumber') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="bankType" class="form-label">Bank</label>
                    <input type="text" name="bankType" id="bankType"
                           class="form-control @error('bankType') is-invalid @enderror"
                           value="{{ old('bankType', $customer->bankType ?? '') }}">
                    @error('bankType') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Customer Status</label>
                    <p class="mb-0">
                        <span class="badge {{ $customer->customerStatus == 'active' ? 'bg-success' : 'bg-danger' }}">
                            {{ ucfirst($customer->customerStatus) }}
                        </span>
                    </p>
                </div>
            </div>
        </div>
        <div class="d-flex justify-content-between">
            <button type="submit" class="btn btn-primary" style="background-color: #dc3545; border-color: #dc3545;">Update Profile</button>
        </div> <div><br></div>

        <!-- Loyalty Card Info -->
        <div class="card mb-4">
            <div class="card-header">Loyalty Card</div>
            <div class="card-body">
                @if($customer->loyaltyCard)
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Referral Code:</strong> {{ $customer->loyaltyCard->referralCode }}</p>
                            <p><strong>Current Stamps:</strong> {{ $customer->loyaltyCard->currentStamp }}</p>
                            <p><strong>Total Stamps:</strong> {{ $customer->loyaltyCard->totalStamp }}</p>
                            <p><strong>Redeemed Stamps:</strong> {{ $customer->loyaltyCard->redeemedStamp }}</p>
                        </div>
                        <div class="col-md-6">
                            <!-- Stamp Progress Bar -->
                            <div class="mb-3">
                                <label>Stamp Progress (10 stamps for free rental)</label>
                                <div class="progress" style="height: 25px;">
                                    @php
                                        $progress = min(100, ($customer->loyaltyCard->currentStamp / 10) * 100);
                                    @endphp
                                    <div class="progress-bar bg-success" style="width: {{ $progress }}%">
                                        {{ $customer->loyaltyCard->currentStamp }}/10
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <p class="text-muted">No loyalty card assigned yet.</p>
                @endif
            </div>
        </div>

        <!-- Referral Info -->
        <div class="card mb-4">
            <div class="card-header">Referral Details</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Total Referrals:</strong> {{ $customer->referral_count ?? 0 }}</p>
                        <p><strong>Next Milestone:</strong>
                            @if(($customer->referral_count ?? 0) < 5)
                                5 referrals → RM2 SNF Pisang Cheese Voucher
                            @elseif(($customer->referral_count ?? 0) < 10)
                                10 referrals → RM5 Pak Atong Cafe Voucher
                            @elseif(($customer->referral_count ?? 0) < 20)
                                20 referrals → 3 Free Rental Days Voucher
                            @else
                                You've unlocked all milestones!
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Voucher Info -->
        <div class="card mb-4">
            <div class="card-header">
                My Vouchers ({{ $customer->vouchers->count() }} total)
            </div>
            <div class="card-body">
                @if($customer->vouchers->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Voucher Code</th>
                                    <th>Type</th>
                                    <th>Details</th>
                                    <th>Status</th>
                                    <th>Expiry Date</th>
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
                                                @elseif($voucher->status == 'expired') bg-danger
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
                                                <button type="submit" class="btn btn-sm btn-success">
                                                    <i class="fas fa-check-circle"></i> Mark as Used
                                                </button>
                                            </form>
                                            
                                            <!-- Success message will appear after redemption -->
                                            @if(session('success') && str_contains(session('success'), $voucher->voucherCode))
                                                <div class="text-success small mt-1">
                                                    <i class="fas fa-check"></i> {{ session('success') }}
                                                </div>
                                            @endif
                                            
                                        @elseif($voucher->pivot->redeemed_at)
                                            <!-- ALREADY REDEEMED -->
                                            <div class="alert alert-success p-2 mb-0" style="display: inline-block;">
                                                <i class="fas fa-check-circle"></i> 
                                                <strong>Used</strong>
                                            </div>
                                        @elseif($voucher->status == 'expired')
                                            <span class="badge bg-danger">Expired</span>
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
                    <div class="text-center py-4">
                        <i class="fas fa-ticket-alt fa-3x text-muted mb-3"></i>
                        <h5>No Vouchers Yet</h5>
                        <p class="text-muted">You haven't earned any vouchers yet.</p>
                        <p>Refer friends to earn vouchers!</p>
                    </div>
                @endif
            </div>
        </div>

        
    </form>
</div>
@endsection

@push('scripts')
<script>
// Remove ALL existing event listeners that might interfere
document.addEventListener('DOMContentLoaded', function() {
    console.log('=== FORM DEBUG ===');
    
    // Find all redemption forms
    const forms = document.querySelectorAll('form[action*="/customer/voucher/"]');
    console.log('Found', forms.length, 'redemption forms');
    
    forms.forEach((form, index) => {
        console.log(`Form ${index}:`, form.action);
        
        // Remove any existing submit handlers
        form.replaceWith(form.cloneNode(true));
        
        // Get fresh reference
        const newForm = document.querySelectorAll('form[action*="/customer/voucher/"]')[index];
        
        // Add clean submit handler
        newForm.addEventListener('submit', function(e) {
            console.log('📤 Form submitting:', this.action);
            console.log('🔑 CSRF token:', this.querySelector('[name="_token"]')?.value?.substring(0, 20) + '...');
            
            // Allow the form to submit normally
            // No e.preventDefault() here!
        });
        
        // Also log clicks
        const button = newForm.querySelector('button[type="submit"]');
        if (button) {
            button.addEventListener('click', function(e) {
                console.log('🖱️ Button clicked for:', this.closest('form').action);
            });
        }
    });
    
    // Global click catcher
    document.addEventListener('click', function(e) {
        if (e.target.closest('form[action*="/customer/voucher/"] button')) {
            console.log('🌍 Global click on redemption button');
        }
    }, true); // Use capture phase
});
</script>
@endpush