{{-- resources/views/staff/commission/edit.blade.php --}}
@extends('layouts.salesperson')

@section('title', 'Edit Commission')

@section('content')
<div class="container">
    <h4 class="mb-3">Edit Commission</h4>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('commission.update', $commission->commissionID) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="mb-3">
            <label class="form-label">Commission Type</label>
            <input type="text" name="commissionType" class="form-control" required 
                   value="{{ old('commissionType', $commission->commissionType) }}">
            @error('commissionType')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Status</label>
            <input type="text" class="form-control" readonly
                   value="{{ ucfirst($commission->status) }}">
            <small class="text-muted">Status cannot be changed</small>
        </div>

        <div class="mb-3">
            <label class="form-label">Applied Date</label>
            <input type="date" name="appliedDate" class="form-control" required
                   value="{{ old('appliedDate', $commission->appliedDate) }}">
            @error('appliedDate')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Amount (RM)</label>
            <input type="number" name="amount" class="form-control" required min="1"
                   value="{{ old('amount', $commission->amount) }}">
            @error('amount')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('commission.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection