{{-- resources/views/inspection/return-with-damage.blade.php --}}
@extends('layouts.customer')

@section('title', 'Vehicle Return Inspection')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-10 offset-md-1">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-undo"></i> Vehicle Return Inspection
                    </h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('inspection.storeReturnInspectionWithDamageCase', $booking->bookingID) }}" 
                          method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- Vehicle Info -->
                        <h5 class="mb-3">Vehicle Information</h5>
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <p><strong>Vehicle:</strong> {{ $booking->vehicle->vehicleName ?? 'N/A' }}</p>
                                <p><strong>Plate No:</strong> {{ $booking->vehicle->plateNo ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Booking ID:</strong> {{ $booking->bookingID }}</p>
                                <p><strong>Return Date:</strong> {{ $booking->return_dateTime->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                        
                        <!-- Basic Inspection -->
                        <h5 class="mb-3">Inspection Details</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Car Condition *</label>
                                    <select name="carCondition" class="form-select" required>
                                        <option value="">Select Condition</option>
                                        <option value="excellent">Excellent</option>
                                        <option value="good">Good</option>
                                        <option value="fair">Fair</option>
                                        <option value="poor">Poor</option>
                                    </select>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Mileage Returned (km) *</label>
                                    <input type="number" name="mileageReturned" class="form-control" 
                                           min="0" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Fuel Level (%) *</label>
                                    <input type="number" name="fuelLevel" class="form-control" 
                                           min="0" max="100" required>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <!-- ⭐⭐⭐ DAMAGE DETECTION (HANYA UNTUK RETURN) ⭐⭐⭐ -->
                                <div class="mb-3">
                                    <label class="form-label">Any Damage Detected? *</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="damageDetected" 
                                               id="noDamage" value="0" checked>
                                        <label class="form-check-label" for="noDamage">
                                            <span class="text-success">
                                                <i class="fas fa-check-circle"></i> No Damage - Vehicle in Good Condition
                                            </span>
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="damageDetected" 
                                               id="hasDamage" value="1">
                                        <label class="form-check-label" for="hasDamage">
                                            <span class="text-danger">
                                                <i class="fas fa-exclamation-triangle"></i> Yes, Damage Found
                                            </span>
                                        </label>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Remarks *</label>
                                    <textarea name="remark" class="form-control" rows="3" 
                                              placeholder="Enter inspection remarks..." required></textarea>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Damage Details Section (Conditional - Only for Return) -->
                        <div class="card border-danger mt-4" id="damageDetailsSection" style="display: none;">
                            <div class="card-header bg-danger text-white">
                                <h5 class="mb-0">
                                    <i class="fas fa-exclamation-triangle"></i> Damage Report Details
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="alert alert-warning">
                                    <i class="fas fa-info-circle"></i> 
                                    Please provide detailed information about the damage. A damage case will be created automatically.
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Damage Type *</label>
                                            <select name="damage_type" class="form-select">
                                                <option value="">Select Type</option>
                                                <option value="Body Damage">Body Damage</option>
                                                <option value="Scratch">Scratch</option>
                                                <option value="Dent">Dent</option>
                                                <option value="Glass Break">Glass Break</option>
                                                <option value="Tire Damage">Tire Damage</option>
                                                <option value="Interior Damage">Interior Damage</option>
                                                <option value="Mechanical Issue">Mechanical Issue</option>
                                            </select>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label class="form-label">Damage Location *</label>
                                            <input type="text" name="damage_location" class="form-control" 
                                                   placeholder="e.g., Front bumper, Driver side door">
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label class="form-label">Severity *</label>
                                            <select name="severity" class="form-select">
                                                <option value="">Select Severity</option>
                                                <option value="low">Low - Minor cosmetic</option>
                                                <option value="medium">Medium - Requires repair</option>
                                                <option value="high">High - Major damage</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Damage Description *</label>
                                            <textarea name="damage_description" class="form-control" rows="3" 
                                                      placeholder="Describe the damage in detail..."></textarea>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label class="form-label">Estimated Repair Cost (RM)</label>
                                            <input type="number" name="estimated_cost" class="form-control" 
                                                   min="0" step="0.01" placeholder="0.00">
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label class="form-label">Damage Photos</label>
                                            <input type="file" name="damage_photos[]" class="form-control" 
                                                   multiple accept="image/*">
                                            <small class="text-muted">Upload clear photos of the damage</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Photo Evidence -->
                        <h5 class="mb-3 mt-4">Photo Evidence</h5>
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Fuel Gauge Evidence</label>
                                    <input type="file" name="fuel_evidence" class="form-control" accept="image/*">
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Front View</label>
                                    <input type="file" name="front_view" class="form-control" accept="image/*">
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Back View</label>
                                    <input type="file" name="back_view" class="form-control" accept="image/*">
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Right Side View</label>
                                    <input type="file" name="right_view" class="form-control" accept="image/*">
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Left Side View</label>
                                    <input type="file" name="left_view" class="form-control" accept="image/*">
                                </div>
                            </div>
                        </div>
                        
                        <!-- Submit Button -->
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('customer.inspections.index') }}" class="btn btn-secondary me-md-2">
                                Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Submit Return Inspection
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Show/hide damage details section
    const damageRadios = document.querySelectorAll('input[name="damageDetected"]');
    const damageSection = document.getElementById('damageDetailsSection');
    
    damageRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.value === '1') {
                damageSection.style.display = 'block';
                // Make damage fields required
                document.querySelectorAll('#damageDetailsSection [name]').forEach(field => {
                    if (field.name !== 'estimated_cost') {
                        field.required = true;
                    }
                });
            } else {
                damageSection.style.display = 'none';
                // Remove required attribute
                document.querySelectorAll('#damageDetailsSection [name]').forEach(field => {
                    field.required = false;
                });
            }
        });
    });
});
</script>
@endsection
@endsection