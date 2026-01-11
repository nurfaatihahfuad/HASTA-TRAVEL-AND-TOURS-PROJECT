{{-- resources/views/staff/inspections/edit.blade.php --}}
@extends('layouts.staff')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h4>Edit Inspection #{{ $inspection->id }}</h4>
            <small>Originally submitted by: {{ $inspection->staffID }} on {{ $inspection->created_at->format('d/m/Y H:i') }}</small>
        </div>
        <div class="card-body">
            <form action="{{ route('staff.inspections.update', $inspection->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Booking ID</label>
                            <input type="text" class="form-control" value="{{ $inspection->bookingID }}" readonly>
                        </div>
                        
                        <div class="form-group">
                            <label>Inspection Type</label>
                            <input type="text" class="form-control" value="{{ strtoupper($inspection->inspectionType) }}" readonly>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Status</label>
                            <select name="status" class="form-control" required>
                                <option value="pending" {{ $inspection->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="verified" {{ $inspection->status == 'verified' ? 'selected' : '' }}>Verified</option>
                                <option value="rejected" {{ $inspection->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <hr>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Car Condition *</label>
                            <input type="text" name="carCondition" class="form-control" 
                                   value="{{ old('carCondition', $inspection->carCondition) }}" required>
                        </div>
                        
                        <div class="form-group">
                            <label>Mileage Returned (km) *</label>
                            <input type="number" name="mileageReturned" class="form-control" 
                                   value="{{ old('mileageReturned', $inspection->mileageReturned) }}" required min="0">
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Fuel Level (%) *</label>
                            <input type="number" name="fuelLevel" class="form-control" 
                                   value="{{ old('fuelLevel', $inspection->fuelLevel) }}" required min="0" max="100">
                        </div>
                        
                        <div class="form-group">
                            <label>Damage Detected *</label>
                            <select name="damageDetected" class="form-control" required>
                                <option value="0" {{ !$inspection->damageDetected ? 'selected' : '' }}>No</option>
                                <option value="1" {{ $inspection->damageDetected ? 'selected' : '' }}>Yes</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Remark *</label>
                    <textarea name="remark" class="form-control" rows="4" required>{{ old('remark', $inspection->remark) }}</textarea>
                </div>
                
                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">Update Inspection</button>
                    <a href="{{ route('staff.inspections.show', $inspection->id) }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection