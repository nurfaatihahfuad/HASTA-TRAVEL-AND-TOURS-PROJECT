@extends('layouts.salesperson')

@section('title', 'Update Inspection')

@section('styles')
<style>
    .inspection-image {
        max-width: 100%;
        height: auto;
        border-radius: 8px;
        margin-bottom: 10px;
    }
    .image-preview {
        border: 2px dashed #ddd;
        padding: 10px;
        border-radius: 8px;
        text-align: center;
        margin-bottom: 15px;
        min-height: 150px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .damage-item {
        border: 1px solid #dee2e6;
        border-radius: 5px;
        padding: 10px;
        margin-bottom: 10px;
    }
    .condition-badge {
        font-size: 0.9rem;
        padding: 5px 10px;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">
            <i class="fas fa-clipboard-check"></i> Update Inspection
        </h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="{{ route('staff.inspections.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle"></i> Please fix the following errors:
        <ul class="mb-0">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="row">
        <div class="col-md-8">
            <!-- Main Form -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-edit"></i> Inspection Details
                    </h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('staff.inspections.update', $inspection->id ?? $inspection->inspectionID) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Basic Information -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Inspection ID</label>
                                    <input type="text" class="form-control" value="{{ $inspection->inspectionID ?? 'N/A' }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Booking ID</label>
                                    <input type="text" class="form-control" value="{{ $inspection->bookingID ?? 'N/A' }}" readonly>
                                </div>
                            </div>
                        </div>

                        <!-- Vehicle Information -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Vehicle</label>
                                    <input type="text" class="form-control" 
                                           value="{{ $inspection->vehicle->vehicleName ?? 'Vehicle #' . $inspection->vehicleID }} ({{ $inspection->vehicle->plateNo ?? 'N/A' }})" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Inspection Type</label>
                                    <input type="text" class="form-control" value="{{ ucfirst($inspection->inspectionType) }}" readonly>
                                </div>
                            </div>
                        </div>

                        <!-- Car Condition -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="carCondition" class="form-label">Car Condition *</label>
                                    <select class="form-select @error('carCondition') is-invalid @enderror" 
                                            id="carCondition" name="carCondition" required>
                                        <option value="">Select Condition</option>
                                        <option value="excellent" {{ old('carCondition', $inspection->carCondition) == 'excellent' ? 'selected' : '' }}>Excellent</option>
                                        <option value="good" {{ old('carCondition', $inspection->carCondition) == 'good' ? 'selected' : '' }}>Good</option>
                                        <option value="fair" {{ old('carCondition', $inspection->carCondition) == 'fair' ? 'selected' : '' }}>Fair</option>
                                        <option value="poor" {{ old('carCondition', $inspection->carCondition) == 'poor' ? 'selected' : '' }}>Poor</option>
                                    </select>
                                    @error('carCondition')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="odometer" class="form-label">Odometer Reading (km)</label>
                                    <input type="number" class="form-control @error('odometer') is-invalid @enderror" 
                                           id="odometer" name="odometer" 
                                           value="{{ old('odometer', $inspection->odometer) }}" 
                                           min="0" step="1">
                                    @error('odometer')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Damage Detection -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label">Damage Detected</label>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="damageDetected" 
                                               id="damageNo" value="0" 
                                               {{ old('damageDetected', $inspection->damageDetected) == 0 ? 'checked' : '' }}>
                                        <label class="form-check-label" for="damageNo">
                                            No Damage
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="damageDetected" 
                                               id="damageYes" value="1"
                                               {{ old('damageDetected', $inspection->damageDetected) == 1 ? 'checked' : '' }}>
                                        <label class="form-check-label" for="damageYes">
                                            Damage Found
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Damage Details (Show if damage detected) -->
                        <div id="damageDetails" class="row mb-4" style="{{ $inspection->damageDetected ? '' : 'display: none;' }}">
                            <div class="col-md-12">
                                <div class="card border-danger">
                                    <div class="card-header bg-danger text-white">
                                        <h6 class="mb-0"><i class="fas fa-exclamation-triangle"></i> Damage Details</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="damageDescription" class="form-label">Damage Description</label>
                                            <textarea class="form-control @error('damageDescription') is-invalid @enderror" 
                                                      id="damageDescription" name="damageDescription" 
                                                      rows="3">{{ old('damageDescription', $inspection->damageDescription) }}</textarea>
                                            @error('damageDescription')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="damageLocation" class="form-label">Damage Location</label>
                                            <select class="form-select @error('damageLocation') is-invalid @enderror" 
                                                    id="damageLocation" name="damageLocation">
                                                <option value="">Select Location</option>
                                                <option value="front" {{ old('damageLocation', $inspection->damageLocation) == 'front' ? 'selected' : '' }}>Front</option>
                                                <option value="rear" {{ old('damageLocation', $inspection->damageLocation) == 'rear' ? 'selected' : '' }}>Rear</option>
                                                <option value="left_side" {{ old('damageLocation', $inspection->damageLocation) == 'left_side' ? 'selected' : '' }}>Left Side</option>
                                                <option value="right_side" {{ old('damageLocation', $inspection->damageLocation) == 'right_side' ? 'selected' : '' }}>Right Side</option>
                                                <option value="roof" {{ old('damageLocation', $inspection->damageLocation) == 'roof' ? 'selected' : '' }}>Roof</option>
                                                <option value="interior" {{ old('damageLocation', $inspection->damageLocation) == 'interior' ? 'selected' : '' }}>Interior</option>
                                                <option value="engine" {{ old('damageLocation', $inspection->damageLocation) == 'engine' ? 'selected' : '' }}>Engine</option>
                                                <option value="other" {{ old('damageLocation', $inspection->damageLocation) == 'other' ? 'selected' : '' }}>Other</option>
                                            </select>
                                            @error('damageLocation')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="repairCost" class="form-label">Estimated Repair Cost (RM)</label>
                                            <input type="number" class="form-control @error('repairCost') is-invalid @enderror" 
                                                   id="repairCost" name="repairCost" 
                                                   value="{{ old('repairCost', $inspection->repairCost) }}" 
                                                   min="0" step="0.01">
                                            @error('repairCost')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Damage Photos -->
                                        <div class="mb-3">
                                            <label class="form-label">Damage Photos</label>
                                            @if($inspection->damagePhotos)
                                            <div class="mb-2">
                                                <strong>Current Photos:</strong>
                                                @php
                                                    $photos = json_decode($inspection->damagePhotos) ?? [$inspection->damagePhotos];
                                                @endphp
                                                @foreach($photos as $photo)
                                                <div class="d-inline-block me-2 mb-2">
                                                    <img src="{{ asset('storage/' . $photo) }}" 
                                                         alt="Damage Photo" 
                                                         class="inspection-image" 
                                                         style="width: 100px; height: 100px; object-fit: cover;">
                                                </div>
                                                @endforeach
                                            </div>
                                            @endif
                                            <input type="file" class="form-control @error('damagePhotos') is-invalid @enderror" 
                                                   id="damagePhotos" name="damagePhotos[]" 
                                                   multiple accept="image/*">
                                            <small class="text-muted">You can upload multiple photos</small>
                                            @error('damagePhotos')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- General Remarks -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="remark" class="form-label">Remarks / Notes</label>
                                    <textarea class="form-control @error('remark') is-invalid @enderror" 
                                              id="remark" name="remark" 
                                              rows="4">{{ old('remark', $inspection->remark) }}</textarea>
                                    @error('remark')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="d-flex justify-content-between">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Update Inspection
                                    </button>
                                    <button type="reset" class="btn btn-secondary">
                                        <i class="fas fa-redo"></i> Reset
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar - Inspection Summary -->
        <div class="col-md-4">
            <!-- Inspection Summary -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-info-circle"></i> Inspection Summary
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Inspection ID:</strong>
                        <div class="text-muted">{{ $inspection->inspectionID ?? 'N/A' }}</div>
                    </div>
                    
                    <div class="mb-3">
                        <strong>Status:</strong>
                        <div>
                            @php
                                $statusClass = [
                                    'pending' => 'warning',
                                    'completed' => 'success',
                                    'approved' => 'info',
                                    'rejected' => 'danger'
                                ][$inspection->status ?? 'pending'] ?? 'secondary';
                            @endphp
                            <span class="badge bg-{{ $statusClass }}">
                                {{ ucfirst($inspection->status ?? 'pending') }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <strong>Inspection Date:</strong>
                        <div class="text-muted">
                            {{ optional($inspection->created_at)->format('d/m/Y H:i') ?? 'N/A' }}
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <strong>Inspected By:</strong>
                        <div class="text-muted">{{ $inspection->staffID ?? 'N/A' }}</div>
                    </div>
                    
                    <div class="mb-3">
                        <strong>Current Condition:</strong>
                        <div>
                            @php
                                $condition = $inspection->carCondition ?? 'unknown';
                                $badgeClass = [
                                    'excellent' => 'success',
                                    'good' => 'primary', 
                                    'fair' => 'warning',
                                    'poor' => 'danger'
                                ][$condition] ?? 'secondary';
                            @endphp
                            <span class="badge bg-{{ $badgeClass }}">
                                {{ ucfirst($condition) }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <strong>Damage Status:</strong>
                        <div>
                            @if($inspection->damageDetected)
                                <span class="badge bg-danger">
                                    <i class="fas fa-exclamation-triangle"></i> Damage Detected
                                </span>
                            @else
                                <span class="badge bg-success">
                                    <i class="fas fa-check"></i> No Damage
                                </span>
                            @endif
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="text-center">
                        <a href="{{ route('staff.inspections.show', $inspection->id ?? $inspection->inspectionID) }}" 
                           class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-eye"></i> View Details
                        </a>
                        
                        @if($inspection->status == 'pending')
                        <form method="POST" action="{{ route('staff.inspections.verify', $inspection->id ?? $inspection->inspectionID) }}" 
                              class="d-inline mt-2">
                            @csrf
                            <button type="submit" class="btn btn-success btn-sm">
                                <i class="fas fa-check-circle"></i> Verify & Complete
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Vehicle Photo Preview -->
            @if($inspection->vehicle && $inspection->vehicle->image_url)
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-car"></i> Vehicle
                    </h6>
                </div>
                <div class="card-body text-center">
                    <img src="{{ asset('storage/' . $inspection->vehicle->image_url) }}" 
                         alt="{{ $inspection->vehicle->vehicleName }}" 
                         class="img-fluid rounded inspection-image">
                    <h6 class="mt-2">{{ $inspection->vehicle->vehicleName }}</h6>
                    <p class="text-muted mb-1">{{ $inspection->vehicle->plateNo }}</p>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle damage details based on radio button
        const damageYes = document.getElementById('damageYes');
        const damageNo = document.getElementById('damageNo');
        const damageDetails = document.getElementById('damageDetails');
        
        function toggleDamageDetails() {
            if (damageYes.checked) {
                damageDetails.style.display = 'block';
                // Make damage fields required
                document.getElementById('damageDescription').required = true;
                document.getElementById('damageLocation').required = true;
            } else {
                damageDetails.style.display = 'none';
                // Remove required from damage fields
                document.getElementById('damageDescription').required = false;
                document.getElementById('damageLocation').required = false;
            }
        }
        
        // Initial check
        toggleDamageDetails();
        
        // Add event listeners
        damageYes.addEventListener('change', toggleDamageDetails);
        damageNo.addEventListener('change', toggleDamageDetails);
        
        // Image preview for new damage photos
        const damagePhotosInput = document.getElementById('damagePhotos');
        if (damagePhotosInput) {
            damagePhotosInput.addEventListener('change', function(e) {
                const previewContainer = document.getElementById('photoPreview');
                if (!previewContainer) {
                    // Create preview container if doesn't exist
                    const container = document.createElement('div');
                    container.id = 'photoPreview';
                    container.className = 'row mt-2';
                    damagePhotosInput.parentNode.appendChild(container);
                } else {
                    previewContainer.innerHTML = '';
                }
                
                Array.from(e.target.files).forEach((file, index) => {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const col = document.createElement('div');
                        col.className = 'col-md-3 mb-2';
                        col.innerHTML = `
                            <div class="image-preview">
                                <img src="${e.target.result}" alt="Preview ${index + 1}" 
                                     style="max-width: 100%; max-height: 100px;">
                                <small class="d-block text-muted">${file.name}</small>
                            </div>
                        `;
                        document.getElementById('photoPreview').appendChild(col);
                    };
                    reader.readAsDataURL(file);
                });
            });
        }
        
        // Form validation
        const form = document.querySelector('form');
        form.addEventListener('submit', function(e) {
            let isValid = true;
            
            // Check if damage detected but no description
            if (damageYes.checked) {
                const damageDesc = document.getElementById('damageDescription').value.trim();
                if (!damageDesc) {
                    alert('Please provide damage description when damage is detected');
                    isValid = false;
                }
            }
            
            if (!isValid) {
                e.preventDefault();
            }
        });
    });
</script>
@endsection