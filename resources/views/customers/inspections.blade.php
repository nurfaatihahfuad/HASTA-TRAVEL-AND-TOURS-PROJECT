@extends('layouts.customer') {{-- Tukar dari salesperson ke customer --}}

@section('title', 'My Inspection Records')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">My Inspection Records</h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('customer.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Inspections</li>
            </ol>
        </nav>
    </div>

    <div class="section-card"> {{-- Menggunakan class section-card dari layout --}}
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="py-3">Type</th>
                        <th class="py-3">Condition</th>
                        <th class="py-3">Mileage</th>
                        <th class="py-3">Fuel</th>
                        <th class="py-3">Damage</th>
                        <th class="py-3">Remark</th>
                    </tr>
                </thead>
                <tbody>
                    @if($inspections->count() > 0)
                        @foreach($inspections as $inspection)
                            <tr>
                                <td>
                                    <span class="badge {{ $inspection->inspectionType == 'pickup' ? 'bg-info' : 'bg-success' }}">
                                        {{ ucfirst($inspection->inspectionType) }}
                                    </span>
                                </td>
                                <td>{{ $inspection->carCondition }}</td>
                                <td class="fw-semibold text-dark">{{ number_format($inspection->mileageReturned) }} km</td>
                                <td>
                                    <div class="progress" style="height: 10px; width: 100px;">
                                        <div class="progress-bar bg-danger" role="progressbar" 
                                             style="width: {{ $inspection->fuelLevel }}%"></div>
                                    </div>
                                    <small class="text-muted">{{ $inspection->fuelLevel }}%</small>
                                </td>
                                <td>
                                    @if($inspection->damageDetected)
                                        <span class="text-danger fw-bold"><i class="fas fa-exclamation-triangle"></i> Yes</span>
                                    @else
                                        <span class="text-success"><i class="fas fa-check-circle"></i> No</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="text-muted small">{{ $inspection->remark ?? 'None' }}</span>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <img src="https://cdn-icons-png.flaticon.com/512/7486/7486744.png" width="80" class="mb-3 opacity-50">
                                <p class="text-muted">No inspection records found.</p>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection