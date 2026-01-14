@extends('layouts.salesperson')

@section('title', 'Edit Damage Case #' . $damageCase->caseID)

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Edit Damage Case #{{ $damageCase->caseID }}</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('staff.damage-cases.update', $damageCase->caseID) }}" method="POST" enctype="multipart/form-data">
                        @csrf @method('PUT')

                        <div class="mb-3">
                            <label class="form-label">Inspection (Optional)</label>
                            <select name="inspectionID" class="form-select">
                                <option value="">Select Inspection</option>
                                @foreach($inspection as $inspection)
                                <option value="{{ $inspection->inspectionID }}" {{ $damageCase->inspectionID == $inspection->inspectionID ? 'selected' : '' }}>
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
                                    <option value="Collision Damage" {{ $damageCase->casetype == 'Collision Damage' ? 'selected' : '' }}>Collision Damage</option>
                                    <option value="Non-Collision Damage" {{ $damageCase->casetype == 'Non-Collision Damage' ? 'selected' : '' }}>Non-Collision Damage</option>
                                    <option value="Technical Damage" {{ $damageCase->casetype == 'Technical Damage' ? 'selected' : '' }}>Technical Damage</option>
                                    <option value="Body Damage" {{ $damageCase->casetype == 'Body Damage' ? 'selected' : '' }}>Body Damage</option>
                                    <option value="Glass Damage" {{ $damageCase->casetype == 'Glass Damage' ? 'selected' : '' }}>Glass Damage</option>
                                    <option value="Total Damage" {{ $damageCase->casetype == 'Total Damage' ? 'selected' : '' }}>Total Damage</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Severity *</label>
                                <select name="severity" class="form-select" required>
                                    <option value="">Select Severity</option>
                                    <option value="Low - Minor cosmetic" {{ $damageCase->severity == 'Low - Minor cosmetic' ? 'selected' : '' }}>Low - Minor cosmetic</option>
                                    <option value="Medium - Requires repair" {{ $damageCase->severity == 'Medium - Requires repair' ? 'selected' : '' }}>Medium - Requires repair</option>
                                    <option value="High - Major damage" {{ $damageCase->severity == 'High - Major damage' ? 'selected' : '' }}>High - Major damage</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Filled By *</label>
                                <input type="text" name="filledby" class="form-control" 
                                       value="{{ $damageCase->filledby }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Status *</label>
                                <select name="resolutionstatus" class="form-select" required>
                                    <option value="In Progress" {{ $damageCase->resolutionstatus == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                                    <option value="Resolved" {{ $damageCase->resolutionstatus == 'Resolved' ? 'selected' : '' }}>Resolved</option>
                                    <option value="Unresolved" {{ $damageCase->resolutionstatus == 'Unresolved' ? 'selected' : '' }}>Unresolved</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Damage Photos (Optional)</label>
                            <input type="file" name="damage_photos[]" class="form-control" multiple>
                            <small class="text-muted">You can select multiple images</small>
                            
                            <!-- Display existing photos if any -->
                            @if($damageCase->damage_photos)
                                <div class="mt-2">
                                    <small>Existing Photos:</small>
                                    <div class="d-flex flex-wrap gap-2 mt-1">
                                        @foreach(json_decode($damageCase->damage_photos) as $photo)
                                            <img src="{{ asset('storage/' . $photo) }}" alt="Damage Photo" style="width: 60px; height: 60px; object-fit: cover;">
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Damage Case
                            </button>
                            <a href="{{ route('staff.damage-cases.show', $damageCase->caseID) }}" class="btn btn-secondary">
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