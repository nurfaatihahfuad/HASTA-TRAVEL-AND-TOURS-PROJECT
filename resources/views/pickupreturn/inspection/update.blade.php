@extends('layouts.customer')
@section('title', 'Edit Inspection')
@section('content')
<div class="container min-h-screen">
    <h2 class="reg-text-primary-dark">Edit Inspection</h2>

    {{-- Alert error --}}
    @if($errors->any())
        <div class="alert alert-danger reg-border-primary-light">
            <ul class="mb-0">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('inspection.update', $inspection->inspectionID) }}" method="POST" enctype="multipart/form-data" class="p-3 reg-bg-primary-light rounded">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label class="required">Car Condition</label>
            <input type="text" name="carCondition" class="form-control reg-focus-ring" value="{{ $inspection->carCondition }}" required>
        </div>

        <div class="form-group">
            <label class="required">Mileage Returned (km)</label>
            <input type="number" name="mileageReturned" class="form-control reg-focus-ring" value="{{ $inspection->mileageReturned }}" required>
        </div>

        <div class="form-group">
            <label class="required">Fuel Level (%)</label>
            <input type="number" name="fuelLevel" class="form-control reg-focus-ring" value="{{ $inspection->fuelLevel }}" required>
        </div>

        <div class="form-group">
            <label class="required">Damage Detected</label><br>
            <label>
                <input type="radio" name="damageDetected" value="1" {{ $inspection->damageDetected == 1 ? 'checked' : '' }}> Yes
            </label>
            <label>
                <input type="radio" name="damageDetected" value="0" {{ $inspection->damageDetected == 0 ? 'checked' : '' }}> No
            </label>
        </div>

        <div class="form-group">
            <label>Remark</label>
            <textarea name="remark" class="form-control reg-focus-ring">{{ $inspection->remark }}</textarea>
        </div>

        <div class="form-group">
            <label>Evidence (image)</label>
            <input type="file" name="evidence" class="form-control reg-focus-ring">
            @if($inspection->evidence)
                <p>Current: <img src="{{ asset('storage/'.$inspection->evidence) }}" width="100"></p>
            @endif
        </div>

        <button type="submit" class="btn btn-outline-secondary px-4">Update Inspection</button>
    </form>
</div>
@endsection