@extends('layouts.it_admin')

@section('title', 'Inspection Details')

@section('styles')
<style>
    .detail-card {
        border-left: 4px solid #4e73df;
        padding-left: 15px;
        margin-bottom: 20px;
    }
    .status-badge {
        font-size: 0.9rem;
        padding: 0.5em 1em;
    }
    .inspection-image {
        max-width: 100%;
        height: auto;
        border-radius: 8px;
        margin-bottom: 10px;
        border: 1px solid #ddd;
    }
    .evidence-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
        margin-top: 10px;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">
            <i class="fas fa-clipboard-check"></i> Inspection Details
        </h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="{{ route('staff.inspections.index') }}" class="btn btn-secondary btn-sm me-2">
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

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="row">
        <!-- Left Column - Inspection Details -->
        <div class="col-md-8">
            <!-- Basic Information Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-info-circle"></i> Basic Information
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="detail-card">
                                <strong>Inspection ID:</strong>
                                <p class="text-muted">{{ $inspection->inspectionID ?? 'N/A' }}</p>
                            </div>
                            <div class="detail-card">
                                <strong>Booking ID:</strong>
                                <p class="text-muted">{{ $inspection->bookingID ?? 'N/A' }}</p>
                            </div>
                            <div class="detail-card">
                                <strong>Inspection Type:</strong>
                                <p class="text-muted">
                                    @php
                                        $type = $inspection->inspectionType ?? 'unknown';
                                        $badgeClass = $type == 'pickup' ? 'bg-info' : 'bg-warning';
                                    @endphp
                                    <span class="badge {{ $badgeClass }}">
                                        {{ strtoupper($type) }}
                                    </span>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="detail-card">
                                <strong>Status:</strong>
                                <p class="text-muted">
                                    @php
                                        $status = $inspection->status ?? 'pending';
                                        $statusClass = [
                                            'pending' => 'warning',
                                            'verified' => 'success',
                                            'rejected' => 'danger'
                                        ][$status] ?? 'secondary';
                                    @endphp
                                    <span class="badge bg-{{ $statusClass }} status-badge">
                                        {{ ucfirst($status) }}
                                    </span>
                                </p>
                            </div>
                            <div class="detail-card">
                                <strong>Created Date:</strong>
                                <p class="text-muted">
                                    {{ optional($inspection->created_at)->format('d/m/Y H:i') ?? 'N/A' }}
                                </p>
                            </div>
                            <div class="detail-card">
                                <strong>Last Updated:</strong>
                                <p class="text-muted">
                                    {{ optional($inspection->updated_at)->format('d/m/Y H:i') ?? 'N/A' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Vehicle & Condition Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-car"></i> Vehicle & Condition
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="detail-card">
                                <strong>Vehicle:</strong>
                                @if(isset($inspection->vehicle) && $inspection->vehicle)
                                <p class="text-muted">
                                    {{ $inspection->vehicle->vehicleName ?? 'N/A' }} 
                                    ({{ $inspection->vehicle->plateNo ?? 'N/A' }})
                                </p>
                                @else
                                <p class="text-muted">Vehicle #{{ $inspection->vehicleID ?? 'N/A' }}</p>
                                @endif
                            </div>
                            <div class="detail-card">
                                <strong>Car Condition:</strong>
                                <p class="text-muted">
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
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="detail-card">
                                <strong>Mileage Returned:</strong>
                                <p class="text-muted">
                                    {{ number_format($inspection->mileageReturned ?? 0) }} km
                                </p>
                            </div>
                            <div class="detail-card">
                                <strong>Fuel Level:</strong>
                                <p class="text-muted">
                                    {{ $inspection->fuelLevel ?? 0 }}%
                                    <div class="progress" style="height: 10px;">
                                        <div class="progress-bar 
                                            @if(($inspection->fuelLevel ?? 0) >= 50) bg-success
                                            @elseif(($inspection->fuelLevel ?? 0) >= 25) bg-warning
                                            @else bg-danger
                                            @endif" 
                                            role="progressbar" 
                                            style="width: {{ $inspection->fuelLevel ?? 0 }}%">
                                        </div>
                                    </div>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Evidence Photos Card -->
            @if($inspection->front_view || $inspection->back_view || $inspection->left_view || $inspection->right_view || $inspection->fuel_evidence)
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-images"></i> Evidence Photos
                    </h6>
                <div class="card shadow mb-4">
    <div class="card-header py-3 bg-white">
        <h6 class="m-0 font-weight-bold text-primary">
            <i class="fas fa-images me-2"></i> Inspection Evidence Photos
        </h6>
    </div>
    <div class="card-body">
        <div class="row g-3"> 
            
            {{-- Front View --}}
            @if($inspection->front_view)
            <div class="col-6 col-md-4 col-lg-2">
                <div class="text-center border rounded p-2 bg-light">
                    <small class="d-block mb-2 fw-bold text-muted">Front View</small>
                    <img src="{{ asset('storage/' . $inspection->front_view) }}" 
                         alt="Front View" 
                         class="img-fluid rounded shadow-sm inspection-thumbnail"
                         onclick="openImageModal(this.src, 'Front View')">
                </div>
            </div>
            @endif

            {{-- Back View --}}
            @if($inspection->back_view)
            <div class="col-6 col-md-4 col-lg-2">
                <div class="text-center border rounded p-2 bg-light">
                    <small class="d-block mb-2 fw-bold text-muted">Back View</small>
                    <img src="{{ asset('storage/' . $inspection->back_view) }}" 
                         alt="Back View" 
                         class="img-fluid rounded shadow-sm inspection-thumbnail"
                         onclick="openImageModal(this.src, 'Back View')">
                </div>
            </div>
            @endif

            {{-- Left View --}}
            @if($inspection->left_view)
            <div class="col-6 col-md-4 col-lg-2">
                <div class="text-center border rounded p-2 bg-light">
                    <small class="d-block mb-2 fw-bold text-muted">Left View</small>
                    <img src="{{ asset('storage/' . $inspection->left_view) }}" 
                         alt="Left View" 
                         class="img-fluid rounded shadow-sm inspection-thumbnail"
                         onclick="openImageModal(this.src, 'Left View')">
                </div>
            </div>
            @endif

            {{-- Right View --}}
            @if($inspection->right_view)
            <div class="col-6 col-md-4 col-lg-2">
                <div class="text-center border rounded p-2 bg-light">
                    <small class="d-block mb-2 fw-bold text-muted">Right View</small>
                    <img src="{{ asset('storage/' . $inspection->right_view) }}" 
                         alt="Right View" 
                         class="img-fluid rounded shadow-sm inspection-thumbnail"
                         onclick="openImageModal(this.src, 'Right View')">
                </div>
            </div>
            @endif

            {{-- Fuel Evidence --}}
            @if($inspection->fuel_evidence)
            <div class="col-6 col-md-4 col-lg-2">
                <div class="text-center border rounded p-2 bg-light">
                    <small class="d-block mb-2 fw-bold text-muted">Fuel Level</small>
                    <img src="{{ asset('storage/' . $inspection->fuel_evidence) }}" 
                         alt="Fuel Evidence" 
                         class="img-fluid rounded shadow-sm inspection-thumbnail"
                         onclick="openImageModal(this.src, 'Fuel Evidence')">
                </div>
            </div>
            @endif

        </div>
    </div>
</div>

{{-- Tambah CSS ini di bahagian atas @section('content') atau dalam fail CSS anda --}}
<style>
    .inspection-thumbnail {
        width: 100%;
        height: 120px; /* Anda boleh tukar ketinggian ini ikut kesesuaian */
        object-fit: cover; /* Ini penting supaya gambar tidak nampak penyek/stretched */
        cursor: pointer;
        transition: transform 0.2s;
    }

    .inspection-thumbnail:hover {
        transform: scale(1.05); /* Efek zoom sikit bila mouse lalu */
    }
</style>
                </div>
            </div>
            @endif

            <!-- Damage Information Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 
                    @if($inspection->damageDetected ?? false) bg-danger text-white
                    @else bg-success text-white
                    @endif">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-exclamation-triangle"></i> Damage Information
                    </h6>
                </div>
                <div class="card-body">
                    <div class="detail-card">
                        <strong>Damage Detected:</strong>
                        <p class="text-muted">
                            @if($inspection->damageDetected ?? false)
                            <span class="badge bg-danger">
                                <i class="fas fa-exclamation-triangle"></i> YES
                            </span>
                            @else
                            <span class="badge bg-success">
                                <i class="fas fa-check"></i> NO
                            </span>
                            @endif
                        </p>
                    </div>

                    @if($inspection->damageDetected ?? false)
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div class="detail-card">
                                <strong>Damage Description:</strong>
                                <p class="text-muted">{{ $inspection->damageDescription ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="detail-card">
                                <strong>Damage Location:</strong>
                                <p class="text-muted">{{ ucfirst(str_replace('_', ' ', $inspection->damageLocation ?? 'N/A')) }}</p>
                            </div>
                        </div>
                    </div>
                    
                    @if($inspection->repairCost)
                    <div class="detail-card mt-3">
                        <strong>Estimated Repair Cost:</strong>
                        <p class="text-muted">RM {{ number_format($inspection->repairCost, 2) }}</p>
                    </div>
                    @endif

                    @if($inspection->damagePhotos)
                    <div class="detail-card mt-3">
                        <strong>Damage Photos:</strong>
                        <div class="row mt-2">
                            @php
                                $photos = json_decode($inspection->damagePhotos) ?? [$inspection->damagePhotos];
                            @endphp
                            @foreach($photos as $photo)
                            @if($photo)
                            <div class="col-md-3 mb-3">
                                <img src="{{ asset('storage/' . $photo) }}" 
                                     alt="Damage Photo" 
                                     class="img-fluid rounded"
                                     style="max-height: 150px; object-fit: cover;">
                            </div>
                            @endif
                            @endforeach
                        </div>
                    </div>
                    @endif
                    @endif
                </div>
            </div>

            <!-- Remarks Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-comment-alt"></i> Remarks
                    </h6>
                </div>
                <div class="card-body">
                    <div class="detail-card">
                        <p class="text-muted">
                            {{ $inspection->remark ?? 'No remarks provided' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column - Sidebar -->
        <div class="col-md-4">
            
            <!-- Vehicle Information -->
            @if(isset($inspection->vehicle) && $inspection->vehicle)
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-car"></i> Vehicle Details
                    </h6>
                </div>
                <div class="card-body">
                    @if($inspection->vehicle->image_url)
                    <div class="text-center mb-3">
                        <img src="{{ asset('storage/' . $inspection->vehicle->image_url) }}" 
                             alt="{{ $inspection->vehicle->vehicleName }}" 
                             class="img-fluid rounded inspection-image"
                             style="max-height: 150px; object-fit: cover;">
                    </div>
                    @endif
                    
                    <div class="detail-card">
                        <strong>Vehicle Name:</strong>
                        <p class="text-muted">{{ $inspection->vehicle->vehicleName ?? 'N/A' }}</p>
                    </div>
                    <div class="detail-card">
                        <strong>Plate Number:</strong>
                        <p class="text-muted">{{ $inspection->vehicle->plateNo ?? 'N/A' }}</p>
                    </div>
                    <div class="detail-card">
                        <strong>Year:</strong>
                        <p class="text-muted">{{ $inspection->vehicle->year ?? 'N/A' }}</p>
                    </div>
                    <div class="detail-card">
                        <strong>Price per Day:</strong>
                        <p class="text-muted">RM {{ number_format($inspection->vehicle->price_per_day ?? 0, 2) }}</p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Customer Information -->
            @if(isset($inspection->booking) && $inspection->booking)
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-user"></i> Customer Information
                    </h6>
                </div>
                <div class="card-body">
                    <div class="detail-card">
                        <strong>Customer ID:</strong>
                        <p class="text-muted">{{ $inspection->booking->userID ?? 'N/A' }}</p>
                    </div>
                    
                    @if(isset($inspection->booking->user) && $inspection->booking->user)
                    <div class="detail-card">
                        <strong>Name:</strong>
                        <p class="text-muted">{{ $inspection->booking->user->name ?? 'N/A' }}</p>
                    </div>
                    <div class="detail-card">
                        <strong>Email:</strong>
                        <p class="text-muted">{{ $inspection->booking->user->email ?? 'N/A' }}</p>
                    </div>
                    <div class="detail-card">
                        <strong>Phone:</strong>
                        <p class="text-muted">{{ $inspection->booking->user->noHP ?? 'N/A' }}</p>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Staff Information -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-user-tie"></i> Inspected By
                    </h6>
                </div>
                <div class="card-body">
                    <div class="detail-card">
                        <strong>Customer Name:</strong>
                        <p class="text-muted">{{ $inspection->staffUser->name ?? 'N/A' }}</p>
                    </div>
                    <div class="detail-card">
                        <strong>Customer ID:</strong>
                        <p class="text-muted">{{ $inspection->staffID ?? 'N/A' }}</p>
                    </div>
                    <div class="detail-card">
                        <strong>Email:</strong>
                        <p class="text-muted">{{ $inspection->staffUser->email ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <!-- Booking Information -->
            @if(isset($inspection->booking) && $inspection->booking)
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-calendar-alt"></i> Booking Details
                    </h6>
                </div>
                <div class="card-body">
                    <div class="detail-card">
                        <strong>Booking Date:</strong>
                        <p class="text-muted">{{ optional($inspection->booking->created_at)->format('d/m/Y') ?? 'N/A' }}</p>
                    </div>
                    <div class="detail-card">
                        <strong>Pickup Date:</strong>
                        <p class="text-muted">{{ optional($inspection->booking->pickup_dateTime)->format('d/m/Y H:i') ?? 'N/A' }}</p>
                    </div>
                    <div class="detail-card">
                        <strong>Return Date:</strong>
                        <p class="text-muted">{{ optional($inspection->booking->return_dateTime)->format('d/m/Y H:i') ?? 'N/A' }}</p>
                    </div>
                    <div class="detail-card">
                        <strong>Booking Status:</strong>
                        <p class="text-muted">
                            <span class="badge bg-{{ $inspection->booking->bookingStatus == 'successful' ? 'success' : 'warning' }}">
                                {{ ucfirst($inspection->booking->bookingStatus ?? 'N/A') }}
                            </span>
                        </p>
                    </div>
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
        // Enable tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Image preview modal
        document.querySelectorAll('.inspection-image').forEach(img => {
            img.addEventListener('click', function() {
                const modal = document.createElement('div');
                modal.className = 'modal fade';
                modal.innerHTML = `
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Image Preview</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body text-center">
                                <img src="${this.src}" class="img-fluid" alt="Preview">
                            </div>
                        </div>
                    </div>
                `;
                document.body.appendChild(modal);
                const modalInstance = new bootstrap.Modal(modal);
                modalInstance.show();
                
                modal.addEventListener('hidden.bs.modal', function () {
                    document.body.removeChild(modal);
                });
            });
        });

        // Verify inspection confirmation
        const verifyForm = document.querySelector('form[action*="verify"]');
        if (verifyForm) {
            verifyForm.addEventListener('submit', function(e) {
                if (!confirm('Are you sure you want to verify this inspection? This action cannot be undone.')) {
                    e.preventDefault();
                }
            });
        }
    });
</script>
@endsection