@extends('layouts.salesperson')

@section('title', 'Create Damage Case')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Create New Damage Case</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('staff.damage-cases.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Inspection (Optional)</label>
                            <select name="inspectionID" class="form-select">
                                <option value="">Select Inspection</option>
                                @foreach($inspections as $inspection)
                                <option value="{{ $inspection->inspectionID }}">
                                    Inspection #{{ $inspection->inspectionID }} - 
                                    {{ $inspection->vehicle->plate_number ?? 'No Vehicle' }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Damage Type *</label>
                                <select name="casetype" class="form-select" required>
                                    <option value="">Select Type</option>
                                    <option value="Collision Damage">Collision Damage</option>
                                    <option value="Non-Collision Damage">Non-Collision Damage</option>
                                     <option value="Technical Damage">Technical Damage</option>
                                      <option value="Body Damage">Body Damage</option>
                                       <option value="Glass Damage">Glass Damage</option>
                                       <option value="Total Damage">Total Damage</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Severity *</label>
                                <select name="severity" class="form-select" required>
                                    <option value="">Select Severity</option>
                                    <option value="Low - Minor cosmetic">Low - Minor cosmetic</option>
                                    <option value="Medium - Requires repair">Medium - Requires repair</option>
                                    <option value="High - Major damage">High - Major damage</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Filled By *</label>
                                <input type="text" name="filledby" class="form-control" 
                                       value="{{ auth()->user()->name }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Status *</label>
                                <select name="resolutionstatus" class="form-select" required>
                                    <option value="In Progress">In Progress</option>
                                    <option value="Resolved">Resolved</option>
                                    <option value="Unresolved">Unresolved</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Damage Photos (Optional)</label>
                            <input type="file" name="damage_photos[]" class="form-control" multiple>
                            <small class="text-muted">You can select multiple images</small>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Create Damage Case
                            </button>
                            <a href="{{ route('staff.damage-cases.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection