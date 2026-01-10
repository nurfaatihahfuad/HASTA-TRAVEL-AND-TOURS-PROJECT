@extends('layouts.customer')

@section('content')
<div class="container">
    <h2 class="mb-4">My Profile</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('customer.profile.update') }}" method="POST">
        @csrf

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
                    <label for="noHP" class="form-label">Phone Number</label>
                    <input type="text" name="noHP" id="noHP"
                           class="form-control @error('noHP') is-invalid @enderror"
                           value="{{ old('noHP', $user->noHP ?? '') }}">
                    @error('noHP') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="accountNumber" class="form-label">Account Number</label>
                    <input type="text" name="accountNumber" id="accountNumber"
                           class="form-control @error('accountNumber') is-invalid @enderror"
                           value="{{ old('accountNumber', $customer->accountNumber) }}">
                    @error('accountNumber') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="bankType" class="form-label">Bank</label>
                    <input type="text" name="bankType" id="bankType"
                           class="form-control @error('bankType') is-invalid @enderror"
                           value="{{ old('bankType', $customer->bankType) }}">
                    @error('bankType') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>

        <!-- Loyalty Card Info -->
        <div class="card mb-4">
            <div class="card-header">Loyalty Card</div>
            <div class="card-body">
                @if($customer->loyaltyCard)
                    <p><strong>Referral Code:</strong> {{ $customer->loyaltyCard->referralCode }}</p>
                    <p><strong>Current Stamps:</strong> {{ $customer->loyaltyCard->currentStamp }}</p>
                    <p><strong>Total Stamps:</strong> {{ $customer->loyaltyCard->totalStamp }}</p>
                    <p><strong>Redeemed Stamps:</strong> {{ $customer->loyaltyCard->redeemedStamp }}</p>
                @else
                    <p class="text-muted">No loyalty card assigned yet.</p>
                @endif
            </div>
        </div>

        <button type="submit" class="btn btn-danger">Update Profile</button>
    </form>
</div>
@endsection