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

           <!-- @if(session('success'))
                <div class="alert alert-success mt-3">
                    {{ session('success') }}
                </div>
            @endif-->
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
                                <p>
                                    <strong>Rating:</strong>
                                    {{ $booking->feedback->rate }} / 5
                                </p>

                                <p>
                                    <strong>Your Review:</strong><br>
                                    {{ $booking->feedback->reviewSentences ?? 'No written feedback provided.' }}
                                </p>
                            @else
                                <p class="text-muted">
                                    You have not submitted feedback for this booking.
                                </p>

                                <button class="btn btn-primary"
                                        data-bs-toggle="modal"
                                        data-bs-target="#feedbackModal">
                                    <i class="fas fa-comment-dots me-1"></i> Leave Feedback
                                </button>
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
<div class="modal fade" id="feedbackModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('feedback.store') }}" method="POST">
            @csrf

            <input type="hidden" name="bookingID" value="{{ $booking->bookingID }}">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-star me-1"></i> Submit Feedback
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Rating</label>
                        <select name="rating" class="form-select" required>
                            <option value="">Select rating</option>
                            <option value="5">★★★★★ Excellent</option>
                            <option value="4">★★★★☆ Good</option>
                            <option value="3">★★★☆☆ Average</option>
                            <option value="2">★★☆☆☆ Poor</option>
                            <option value="1">★☆☆☆☆ Very Poor</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Review (Optional)</label>
                        <textarea name="review"
                                  class="form-control"
                                  rows="3"
                                  placeholder="Share your rental experience..."></textarea>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-paper-plane me-1"></i> Submit
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection
