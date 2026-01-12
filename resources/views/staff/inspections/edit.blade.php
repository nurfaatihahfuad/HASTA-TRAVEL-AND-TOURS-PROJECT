@extends('layouts.salesperson') {{-- Sesuaikan dengan layout anda --}}

@section('content')
<div class="container py-4">
    <div class="card shadow-sm border-0" style="border-radius: 20px;">
        <div class="card-body p-4">
            <h4 class="fw-bold mb-4">Edit Inspection #{{ $inspection->inspectionID }}</h4>

            <form action="{{ route('staff.inspections.update', $inspection->inspectionID) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Car Condition</label>
                        <select name="carCondition" class="form-select">
                            @foreach(['excellent', 'good', 'fair', 'poor'] as $cond)
                                <option value="{{ $cond }}" {{ $inspection->carCondition == $cond ? 'selected' : '' }}>{{ ucfirst($cond) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold">Fuel Level (%)</label>
                        <input type="number" name="fuelLevel" class="form-control" value="{{ $inspection->fuelLevel }}">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold">Mileage</label>
                        <input type="number" name="mileageReturned" class="form-control" value="{{ $inspection->mileageReturned }}">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Inspection Status</label>
                        <select name="status" class="form-select border-primary">
                            <option value="pending" {{ $inspection->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="verified" {{ $inspection->status == 'verified' ? 'selected' : '' }}>Verified</option>
                            <option value="rejected" {{ $inspection->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Damage Detected?</label>
                        <select name="damageDetected" id="damageSelect" class="form-select">
                            <option value="0" {{ !$inspection->damageDetected ? 'selected' : '' }}>No</option>
                            <option value="1" {{ $inspection->damageDetected ? 'selected' : '' }}>Yes</option>
                        </select>
                    </div>

                    <div class="col-12 mb-3">
                        <label class="form-label fw-bold">Staff Remark</label>
                        <textarea name="remark" class="form-control" rows="3">{{ $inspection->remark }}</textarea>
                    </div>
                </div>

                <div id="damageSection" class="p-3 mb-3 border-start border-danger border-4 bg-light" 
                     style="display: {{ $inspection->damageDetected ? 'block' : 'none' }}; border-radius: 10px;">
                    <h5 class="text-danger fw-bold mb-3">🛠️ Damage Case Details</h5>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Damage Type</label>
                            <input type="text" name="damageType" class="form-control" value="{{ optional($inspection->damageCase)->damageType ?? 'Collision Damage' }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Severity</label>
                            <select name="severity" class="form-select">
                                <option value="low" {{ optional($inspection->damageCase)->severity == 'low' ? 'selected' : '' }}>Low</option>
                                <option value="medium" {{ optional($inspection->damageCase)->severity == 'medium' ? 'selected' : '' }}>Medium</option>
                                <option value="high" {{ optional($inspection->damageCase)->severity == 'high' ? 'selected' : '' }}>High</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Damage Status</label>
                            <select name="resolutionstatus" class="form-select bg-warning-subtle">
                                <option value="Unresolved" {{ optional($inspection->damageCase)->resolutionstatus == 'Unresolved' ? 'selected' : '' }}>Unresolved</option>
                                <option value="Resolved" {{ optional($inspection->damageCase)->resolutionstatus == 'Resolved' ? 'selected' : '' }}>Resolved</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-dark px-5">Save Updates</button>
                    <a href="{{ route('staff.inspections.index') }}" class="btn btn-outline-secondary px-4">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('damageSelect').addEventListener('change', function() {
        document.getElementById('damageSection').style.display = (this.value == '1') ? 'block' : 'none';
    });
</script>
@endsection