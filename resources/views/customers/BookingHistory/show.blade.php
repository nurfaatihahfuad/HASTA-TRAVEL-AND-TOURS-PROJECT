@extends('layouts.customer')

@section('title', 'Booking Details')

@push('styles')
<style>
    .card {
        border: none;
        border-radius: 14px;
        box-shadow: 0 6px 18px rgba(0,0,0,0.06);
    }

    .section-title {
        font-weight: 600;
        margin-bottom: 12px;
    }

    .info-item {
        margin-bottom: 6px;
    }

    .icon-label i {
        width: 20px;
    }

    .qr-box {
        border: 1px dashed #ced4da;
        border-radius: 12px;
        padding: 16px;
        text-align: center;
        background: #f9fafb;
    }

    .action-btns a {
        min-width: 130px;
    }

    /* Star rating styling */
    .star-rating {
        font-size: 28px;
        color: #ffc107;
        cursor: pointer;
    }

    .star-rating .bi-star {
        color: #dee2e6;
    }

    .star-rating .selected {
        color: #ffc107;
    }

    /* Modal styling */
    .feedback-modal .modal-content {
        border-radius: 16px;
        border: none;
        box-shadow: 0 10px 40px rgba(0,0,0,0.15);
    }

    .feedback-modal .modal-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 16px 16px 0 0;
        padding: 1.5rem 2rem;
    }

    .feedback-modal .modal-body {
        padding: 2rem;
    }

    .feedback-modal .modal-footer {
        border-top: 1px solid #eef2f7;
        padding: 1.5rem 2rem;
    }

    /* Rating options styling */
    .rating-options {
        display: flex;
        justify-content: center;
        gap: 12px;
        margin-bottom: 20px;
    }

    .rating-option {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 15px 20px;
        border: 2px solid #eef2f7;
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.3s ease;
        min-width: 100px;
    }

    .rating-option:hover {
        border-color: #667eea;
        background-color: #f8f9ff;
        transform: translateY(-2px);
    }

    .rating-option.selected {
        border-color: #667eea;
        background-color: #f8f9ff;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.15);
    }

    .rating-stars {
        font-size: 24px;
        color: #ffc107;
        margin-bottom: 8px;
    }

    .rating-label {
        font-size: 14px;
        font-weight: 600;
        color: #4a5568;
    }

    .rating-desc {
        font-size: 12px;
        color: #718096;
        margin-top: 4px;
    }
</style>
@endpush

@section('content')
<div class="container py-3">

    <!-- Back -->
    <a href="{{ route('customers.BookingHistory.index',$booking->bookingID) }}"
       class="btn btn-sm btn-outline-secondary mb-3">
        <i class="fas fa-arrow-left me-1"></i> Back
    </a>

    <!-- Booking Summary -->
    <div class="card mb-4">
        <div class="card-header bg-white fw-bold">
            Booking Details
            <span class="text-muted">#{{ $booking->bookingID }}</span>
        </div>

        <div class="card-body row g-4">
            <div class="col-md-6">
                <div class="section-title">
                    <i class="fas fa-car me-1"></i> Vehicle Information
                </div>
                <div class="info-item"><strong>Name:</strong> {{ $booking->vehicle->vehicleName }}</div>
                <div class="info-item"><strong>Plate No:</strong> {{ $booking->vehicle->plateNo ?? '-' }}</div>
            </div>

            <div class="col-md-6">
                <div class="section-title">
                    <i class="fas fa-calendar-alt me-1"></i> Booking Information
                </div>
                <div class="info-item">
                    <strong>Pickup:</strong>
                    {{ \Carbon\Carbon::parse($booking->pickup_dateTime)->format('d M Y, h:i A') }}
                </div>
                <div class="info-item">
                    <strong>Return:</strong>
                    {{ \Carbon\Carbon::parse($booking->return_dateTime)->format('d M Y, h:i A') }}
                </div>
                <div class="info-item"><strong>Total Hours:</strong> {{ $totalHours }}</div>
                <div class="info-item text-success">
                    <strong>Total Price:</strong> RM {{ number_format($totalPayment, 2) }}
                </div>
            </div>
        </div>
    </div>

    <!-- Payment -->
    <div class="card mb-4">
        <div class="card-header bg-white fw-bold">
            <i class="fas fa-wallet me-1"></i> Payment Details
        </div>

        <div class="card-body">
            @if($payment)
                <p><strong>Payment Type:</strong>
                    <span class="badge bg-info">{{ $payment->paymentType }}</span>
                </p>

                <p><strong>Amount Paid:</strong>
                    RM {{ number_format($payment->amountPaid, 2) }}
                </p>

                @if($payment->paymentType === 'Deposit Payment')
                    <div class="qr-box my-3">
                        <p class="fw-semibold mb-2">Scan to Pay</p>
                        <img src="{{ asset('img/payment.png') }}" width="140" class="mb-3">

                        <form action="{{ route('payment.uploadReceipt', $payment->paymentID) }}"
                              method="POST"
                              enctype="multipart/form-data">
                            @csrf
                            <input type="file" name="receipt_file" class="form-control mb-2" required>
                            <button class="btn btn-primary w-100">
                                <i class="fas fa-upload me-1"></i> Upload Receipt
                            </button>
                        </form>
                    </div>
                @endif

                @if($payment->receipt_file_path)
                    <p>
                        <strong>Proof:</strong>
                        <a href="{{ asset('storage/' . $payment->receipt_file_path) }}" target="_blank">
                            View Uploaded Receipt
                        </a>
                    </p>
                @endif
            @else
                <p class="text-muted"><em>No payment record found.</em></p>
            @endif
        </div>
    </div>

    <!-- Actions -->
    <div class="card">
        <div class="card-header bg-white fw-bold">
            <i class="fas fa-tasks me-1"></i> Booking Actions
        </div>

        <div class="card-body">

            {{-- COMPLETED BOOKING --}}
            @if($booking->bookingStatus === 'completed')

                <h6 class="mb-3 text-success">
                    <i class="fas fa-check-circle me-1"></i> Booking Completed
                </h6>

                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="border rounded p-3 h-100">
                            <h6 class="fw-semibold">Pickup Inspection</h6>
                            @if($pickupInspection)
                                <p><strong>Date:</strong>
                                    {{ \Carbon\Carbon::parse($pickupInspection->created_at)->format('d M Y, h:i A') }}
                                </p>
                                <p><strong>Condition:</strong> {{ $pickupInspection->carCondition ?? '-' }}</p>
                                <p><strong>Remarks:</strong> {{ $pickupInspection->remark ?? '-' }}</p>
                            @else
                                <p class="text-muted">No pickup inspection record</p>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="border rounded p-3 h-100">
                            <h6 class="fw-semibold">Return Inspection</h6>
                            @if($returnInspection)
                                <p><strong>Date:</strong>
                                    {{ \Carbon\Carbon::parse($returnInspection->created_at)->format('d M Y, h:i A') }}
                                </p>
                                <p><strong>Condition:</strong> {{ $returnInspection->carCondition ?? '-' }}</p>
                                <p><strong>Remarks:</strong> {{ $returnInspection->remark ?? '-' }}</p>
                            @else
                                <p class="text-muted">No return inspection record</p>
                            @endif
                        </div>
                    </div>
                </div>

                @if($booking->bookingStatus === 'completed')
                    {{-- FEEDBACK SECTION --}}
                    <div class="card mt-4">
                        <div class="card-header bg-white fw-bold">
                            <i class="fas fa-star me-1"></i> Rental Feedback
                        </div>

                        <div class="card-body">
                            {{-- SUCCESS MESSAGE (ONLY FOR FEEDBACK) --}}
                            @if(session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif

                            @if($booking->feedback)
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="rating-display mb-3">
                                            <div class="d-flex align-items-center mb-2">
                                                <div class="star-rating me-2">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        @if($i <= $booking->feedback->rate)
                                                            <i class="fas fa-star"></i>
                                                        @else
                                                            <i class="far fa-star"></i>
                                                        @endif
                                                    @endfor
                                                </div>
                                                <span class="fw-bold fs-5">{{ $booking->feedback->rate }}.0</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="border-start ps-4">
                                            <h6 class="fw-semibold mb-2">Your Review</h6>
                                            <p class="mb-0">
                                                {{ $booking->feedback->reviewSentences ?? 'No written feedback provided.' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <div class="mb-3">
                                        <i class="fas fa-comment-alt fa-3x text-muted mb-3"></i>
                                        <h5 class="text-muted">No Feedback Submitted</h5>
                                        <p class="text-muted mb-4">
                                            Share your experience to help us improve our service
                                        </p>
                                    </div>
                                    <button class="btn btn-primary btn-lg px-4"
                                            data-bs-toggle="modal"
                                            data-bs-target="#feedbackModal">
                                        <i class="fas fa-comment-dots me-2"></i> Leave Feedback
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

            {{-- NOT COMPLETED --}}
            @else

                <p class="text-muted mb-3">
                    Please complete the inspection forms during pickup and return.
                </p>

                <div class="d-flex justify-content-end gap-2">

                    {{-- ENABLE ONLY IF NO PICKUP & NO RETURN --}}
                    @if(!$pickupInspection && !$returnInspection)

                        <a href="{{ route('inspection.pickupInspection', $booking->bookingID) }}"
                        class="btn btn-success">
                            <i class="fas fa-key me-1"></i> Pickup
                        </a>

                        <a href="{{ route('inspection.returnInspection', $booking->bookingID) }}"
                        class="btn btn-warning text-white">
                            <i class="fas fa-undo me-1"></i> Return
                        </a>

                    @else
                        <button class="btn btn-secondary" disabled>
                            Inspection In Progress
                        </button>
                    @endif

                </div>

            @endif
        </div>
    </div>
</div>

{{-- FEEDBACK MODAL --}}
<div class="modal fade feedback-modal" id="feedbackModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <form action="{{ route('feedback.store') }}" method="POST">
            @csrf

            <input type="hidden" name="bookingID" value="{{ $booking->bookingID }}">
            <input type="hidden" name="rating" id="selectedRating" value="" required>

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-star me-2"></i> Share Your Experience
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <!-- Vehicle Info -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="d-flex align-items-center p-3 bg-light rounded">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-car fa-2x text-primary"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-1">{{ $booking->vehicle->vehicleName }}</h6>
                                    <p class="text-muted mb-0 small">
                                        Booking #{{ $booking->bookingID }} • 
                                        {{ \Carbon\Carbon::parse($booking->pickup_dateTime)->format('d M Y') }} - 
                                        {{ \Carbon\Carbon::parse($booking->return_dateTime)->format('d M Y') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Rating Selection -->
                    <div class="mb-4">
                        <label class="form-label fw-bold fs-5 mb-3">
                            How would you rate your rental experience?
                        </label>
                        
                        <div class="rating-options">
                            @php
                                $ratings = [
                                    1 => ['stars' => '★', 'label' => 'Poor', 'desc' => 'Very Dissatisfied'],
                                    2 => ['stars' => '★★', 'label' => 'Fair', 'desc' => 'Needs Improvement'],
                                    3 => ['stars' => '★★★', 'label' => 'Good', 'desc' => 'Satisfied'],
                                    4 => ['stars' => '★★★★', 'label' => 'Very Good', 'desc' => 'Happy'],
                                    5 => ['stars' => '★★★★★', 'label' => 'Excellent', 'desc' => 'Delighted'],
                                ];
                            @endphp
                            
                            @foreach($ratings as $value => $rating)
                                <div class="rating-option" data-rating="{{ $value }}">
                                    <div class="rating-stars">
                                        @for($i = 0; $i < $value; $i++)
                                            <i class="fas fa-star"></i>
                                        @endfor
                                        @for($i = $value; $i < 5; $i++)
                                            <i class="far fa-star"></i>
                                        @endfor
                                    </div>
                                    <span class="rating-label">{{ $rating['label'] }}</span>
                                    <span class="rating-desc">{{ $rating['desc'] }}</span>
                                </div>
                            @endforeach
                        </div>
                        
                        <div id="ratingError" class="text-danger small mt-2" style="display: none;">
                            Please select a rating
                        </div>
                    </div>

                    <!-- Review Text -->
                    <div class="mb-3">
                        <label class="form-label fw-bold mb-2">
                            <i class="fas fa-comment me-1"></i> Tell us more (Optional)
                        </label>
                        <textarea name="review"
                                  class="form-control form-control-lg"
                                  rows="5"
                                  placeholder="What did you like about your rental experience? Is there anything we could improve? Your feedback helps us serve you better..."
                                  style="font-size: 1rem;"></textarea>
                    </div>

                    <!-- Optional Questions -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="recommend" id="recommend">
                                <label class="form-check-label" for="recommend">
                                    I would recommend this service
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="rent_again" id="rent_again">
                                <label class="form-check-label" for="rent_again">
                                    I would rent again
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-success px-4" id="submitFeedback">
                        <i class="fas fa-paper-plane me-2"></i> Submit Feedback
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ratingOptions = document.querySelectorAll('.rating-option');
    const selectedRatingInput = document.getElementById('selectedRating');
    const ratingError = document.getElementById('ratingError');
    const submitBtn = document.getElementById('submitFeedback');
    const feedbackForm = document.querySelector('form[action*="feedback.store"]');

    // Handle rating selection
    ratingOptions.forEach(option => {
        option.addEventListener('click', function() {
            // Remove previous selection
            ratingOptions.forEach(opt => opt.classList.remove('selected'));
            
            // Add selection to clicked option
            this.classList.add('selected');
            
            // Set the rating value
            const rating = this.dataset.rating;
            selectedRatingInput.value = rating;
            
            // Hide error if showing
            ratingError.style.display = 'none';
        });
    });

    // Form validation
    feedbackForm.addEventListener('submit', function(e) {
        if (!selectedRatingInput.value) {
            e.preventDefault();
            ratingError.style.display = 'block';
            
            // Scroll to rating section
            ratingError.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    });

    // Auto-focus on modal when opened
    const feedbackModal = document.getElementById('feedbackModal');
    if (feedbackModal) {
        feedbackModal.addEventListener('shown.bs.modal', function () {
            // Focus on first rating option
            ratingOptions[4].focus();
        });
    }
});
</script>
@endpush