@extends('layouts.salesperson')

@section('title', 'Damage Case #' . $damageCase->caseID)

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-car-crash"></i> Damage Case #{{ $damageCase->caseID }}</h2>
        <div>
            <a href="{{ route('staff.damage-cases.edit', $damageCase->caseID) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Edit
            </a>
            <a href="{{ route('staff.damage-cases.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5>Case Details</h5>
                </div>
                <div class="card-body">
                    <table class="table">
                        <tr>
                            <th width="30%">Case ID:</th>
                            <td>#{{ $damageCase->caseID }}</td>
                        </tr>
                        <tr>
                            <th>Damage Type:</th>
                            <td>{{ $damageCase->casetype }}</td>
                        </tr>
                        <tr>
                            <th>Severity:</th>
                            <td>
                                <span class="badge bg-{{ $damageCase->severity == 'High - Major damage' ? 'danger' : ($damageCase->severity == 'Medium - Requires repair' ? 'warning' : 'success') }}">
                                    {{ $damageCase->severity }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Status:</th>
                            <td>{!! $damageCase->status_badge !!}</td>
                        </tr>
                        <tr>
                            <th>Filled By:</th>
                            <td>{{ $damageCase->filledby }}</td>
                        </tr>
                        @if($damageCase->inspection)
                        <tr>
                            <th>Inspection ID:</th>
                            <td>
                                <a href="#">{{ $damageCase->inspection->inspectionID }}</a>
                                @if($damageCase->inspection->vehicle)
                                <br><small>Vehicle: {{ $damageCase->inspection->vehicle->plate_number }}</small>
                                @endif
                            </td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>

            <!-- Photos Section -->
            @if(!empty($damageCase->photos_array))
            <div class="card mt-4">
                <div class="card-header">
                    <h5>Damage Photos</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($damageCase->photos_array as $photo)
                        <div class="col-md-3 mb-3">
                            <a href="{{ asset('storage/damage-photos/' . $photo) }}" target="_blank">
                                <img src="{{ asset('storage/damage-photos/' . $photo) }}" 
                                     class="img-thumbnail" 
                                     style="width: 100%; height: 150px; object-fit: cover;">
                            </a>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5>Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('staff.damage-cases.edit', $damageCase->caseID) }}" 
                           class="btn btn-primary">
                            <i class="fas fa-edit"></i> Update Status
                        </a>
                        @if($damageCase->inspection)
                        <a href="#" class="btn btn-info">
                            <i class="fas fa-file-invoice"></i> View Inspection
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection