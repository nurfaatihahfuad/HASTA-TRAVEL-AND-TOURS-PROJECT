@extends('layouts.staff')

@section('title', 'Edit Damage Case #' . $damageCase->caseID)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">
                        <i class="fas fa-edit"></i> Edit Damage Case #{{ $damageCase->caseID }}
                    </h4>
                </div>
                <form action="{{ route('staff.damage-cases.update', $damageCase) }}" method="POST" enctype="multipart/form-data">
                    @csrf @method('PUT')
                    
                    <div class="card-body">
                        <!-- Basic Information -->
                        <div class="mb-4">
                            <h6 class="border-bottom pb-2 mb-3">Basic Information</h6>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Case Type *</label>
                                    <select name="casetype" class="form-select" required>
                                        <option value="">Select Type</option>
                                        @foreach($caseTypes as $type)
                                        <option value="{{ $type }}" 
                                                {{ $damageCase->casetype == $type ? 'selected' : '' }}>
                                            {{ $type }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Severity *</label>
                                    <select name="severity" class="form-select" required>
                                        <option value="">Select Severity</option>
                                        @foreach($severities as $severity)
                                        <option value="{{ $severity }}" 
                                                {{ $damageCase->severity == $severity ? 'selected' : '' }}>
                                            {{ $severity }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Damage Description *</label>
                                <textarea name="damage_description" class="form-control" 
                                          rows="4" required>{{ old('damage_description', $damageCase->damage_description) }}</textarea>
                                <small class="text-muted">Detailed description of the damage</small>
                            </div>
                        </div>

                        <!-- Financial Information -->
                        <div class="mb-4">
                            <h6 class="border-bottom pb-2 mb-3">Financial Information</h6>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Estimated Cost (RM) *</label>
                                    <input type="number" name="estimated_cost" class="form-control" 
                                           step="0.01" min="0" required
                                           value="{{ old('estimated_cost', $damageCase->estimated_cost) }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Actual Cost (RM)</label>
                                    <input type="number" name="actual_cost" class="form-control" 
                                           step="0.01" min="0"
                                           value="{{ old('actual_cost', $damageCase->actual_cost) }}">
                                    <small class="text-muted">Leave blank if not yet known</small>
                                </div>
                            </div>
                        </div>

                        <!-- Assignment & Timeline -->
                        <div class="mb-4">
                            <h6 class="border-bottom pb-2 mb-3">Assignment & Timeline</h6>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Assign To</label>
                                    <select name="assigned_to" class="form-select">
                                        <option value="">Unassigned</option>
                                        @foreach($staffUsers as $user)
                                        <option value="{{ $user->userID }}" 
                                                {{ $damageCase->assigned_to == $user->userID ? 'selected' : '' }}>
                                            {{ $user->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Priority</label>
                                    <select name="priority" class="form-select" required>
                                        <option value="low" {{ $damageCase->priority == 'low' ? 'selected' : '' }}>Low</option>
                                        <option value="medium" {{ $damageCase->priority == 'medium' ? 'selected' : '' }}>Medium</option>
                                        <option value="high" {{ $damageCase->priority == 'high' ? 'selected' : '' }}>High</option>
                                        <option value="urgent" {{ $damageCase->priority == 'urgent' ? 'selected' : '' }}>Urgent</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Due Date</label>
                                    <input type="date" name="due_date" class="form-control"
                                           value="{{ old('due_date', $damageCase->due_date ? $damageCase->due_date->format('Y-m-d') : '') }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Status *</label>
                                    <select name="resolutionstatus" class="form-select" required>
                                        @foreach($statuses as $status)
                                        <option value="{{ $status }}" 
                                                {{ $damageCase->resolutionstatus == $status ? 'selected' : '' }}>
                                            {{ $status }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Resolution Details -->
                        <div class="mb-4" id="resolutionDetails" 
                             style="{{ in_array($damageCase->resolutionstatus, ['Resolved', 'Closed']) ? '' : 'display:none;' }}">
                            <h6 class="border-bottom pb-2 mb-3">Resolution Details</h6>
                            <div class="mb-3">
                                <label class="form-label">Resolution Notes</label>
                                <textarea name="resolution_notes" class="form-control" rows="4">{{ old('resolution_notes', $damageCase->resolution_notes) }}</textarea>
                                <small class="text-muted">Details about how the damage was resolved</small>
                            </div>
                        </div>

                        <!-- Damage Photos -->
                        <div class="mb-4">
                            <h6 class="border-bottom pb-2 mb-3">Damage Photos</h6>
                            
                            <!-- Existing Photos -->
                            @if($damageCase->hasMedia('damage_photos'))
                            <div class="row mb-3">
                                <div class="col-12">
                                    <label class="form-label">Current Photos</label>
                                    <div class="row" id="existingPhotos">
                                        @foreach($damageCase->getMedia('damage_photos') as $photo)
                                        <div class="col-md-3 mb-2 position-relative">
                                            <img src="{{ $photo->getUrl('thumb') }}" 
                                                 class="img-thumbnail w-100" 
                                                 style="height: 100px; object-fit: cover;">
                                            <button type="button" 
                                                    class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1 delete-photo"
                                                    data-photo-id="{{ $photo->id }}">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            @endif

                            <!-- Upload New Photos -->
                            <div class="mb-3">
                                <label class="form-label">Add More Photos</label>
                                <input type="file" name="damage_photos[]" 
                                       class="form-control" multiple accept="image/*">
                                <small class="text-muted">Max 5MB per image. You can select multiple files.</small>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="d-flex justify-content-between">
                            <div>
                                <a href="{{ route('staff.damage-cases.show', $damageCase) }}" 
                                   class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Cancel
                                </a>
                            </div>
                            <div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Update Damage Case
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Sidebar - Case Information -->
        <div class="col-lg-4">
            <!-- Case Summary -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">Case Summary</h6>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <td><strong>Case ID:</strong></td>
                            <td>#{{ $damageCase->caseID }}</td>
                        </tr>
                        <tr>
                            <td><strong>Created:</strong></td>
                            <td>{{ $damageCase->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Created By:</strong></td>
                            <td>{{ $damageCase->filledby }}</td>
                        </tr>
                        @if($damageCase->resolved_at)
                        <tr>
                            <td><strong>Resolved:</strong></td>
                            <td>{{ $damageCase->resolved_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>

            <!-- Vehicle Information -->
            @if($damageCase->inspection && $damageCase->inspection->vehicle)
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">Vehicle Information</h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        @if($damageCase->inspection->vehicle->photo)
                        <img src="{{ asset($damageCase->inspection->vehicle->photo) }}" 
                             class="img-fluid rounded" style="max-height: 150px;">
                        @else
                        <div class="bg-light rounded py-4">
                            <i class="fas fa-car fa-3x text-muted"></i>
                        </div>
                        @endif
                    </div>
                    <table class="table table-sm">
                        <tr>
                            <td><strong>Plate:</strong></td>
                            <td>{{ $damageCase->inspection->vehicle->plate_number }}</td>
                        </tr>
                        <tr>
                            <td><strong>Model:</strong></td>
                            <td>{{ $damageCase->inspection->vehicle->model }}</td>
                        </tr>
                        <tr>
                            <td><strong>Year:</strong></td>
                            <td>{{ $damageCase->inspection->vehicle->year }}</td>
                        </tr>
                    </table>
                </div>
            </div>
            @endif

            <!-- Quick Actions -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        @if($damageCase->inspection && $damageCase->inspection->booking)
                        <a href="{{ route('staff.bookings.show', $damageCase->inspection->booking) }}" 
                           class="btn btn-outline-primary">
                            <i class="fas fa-file-invoice"></i> View Booking
                        </a>
                        @endif
                        
                        <button type="button" class="btn btn-outline-warning" id="printCaseBtn">
                            <i class="fas fa-print"></i> Print Case Details
                        </button>
                        
                        <button type="button" class="btn btn-outline-info" data-bs-toggle="modal" 
                                data-bs-target="#activityModal">
                            <i class="fas fa-history"></i> View Activity Log
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Activity Modal -->
<div class="modal fade" id="activityModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Activity Log - Case #{{ $damageCase->caseID }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <!-- Activity log will be loaded here -->
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Show/hide resolution details based on status
    $('select[name="resolutionstatus"]').change(function() {
        if ($(this).val() === 'Resolved' || $(this).val() === 'Closed') {
            $('#resolutionDetails').slideDown();
            $('textarea[name="resolution_notes"]').prop('required', true);
        } else {
            $('#resolutionDetails').slideUp();
            $('textarea[name="resolution_notes"]').prop('required', false);
        }
    });

    // Delete photo
    $('.delete-photo').click(function() {
        const photoId = $(this).data('photo-id');
        const caseId = {{ $damageCase->caseID }};
        
        if (confirm('Are you sure you want to delete this photo?')) {
            $.ajax({
                url: `/staff/damage-cases/${caseId}/photos/${photoId}`,
                method: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    $(this).closest('.col-md-3').remove();
                    toastr.success('Photo deleted successfully');
                }.bind(this),
                error: function() {
                    toastr.error('Failed to delete photo');
                }
            });
        }
    });

    // Load activity log
    $('#activityModal').on('show.bs.modal', function() {
        const modalBody = $(this).find('.modal-body');
        modalBody.load('/staff/damage-cases/{{ $damageCase->caseID }}/activity');
    });

    // Print functionality
    $('#printCaseBtn').click(function() {
        window.open('/staff/damage-cases/{{ $damageCase->caseID }}/print', '_blank');
    });
});
</script>
@endpush