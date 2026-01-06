@extends('layouts.runner')
@section('title', 'Update Damage Case')

@section('content')
<div class="container">
    <h2 class="reg-text-primary-dark">Update Damage Case</h2>

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

    <form action="{{ route('damagecase.update', ['damagecase' => $case->caseID]) }}" 
          method="POST" class="p-3 reg-bg-primary-light rounded">
        @csrf
        @method('PUT')

        <div class="form-group mb-3">
            <label for="inspectionID" class="form-label">Inspection ID</label>
            <input type="text" name="inspectionID" class="form-control reg-focus-ring"
                   value="{{ $case->inspectionID }}" readonly>
        </div>

        <div class="form-group mb-3">
            <label for="casetype" class="form-label">Case Type</label>
            <select name="casetype" class="form-control reg-focus-ring" required>
                <option value="Collision Damage" {{ $case->casetype == 'Collision Damage' ? 'selected' : '' }}>Collision Damage</option>
                <option value="Non-Collision Damage" {{ $case->casetype == 'Non-Collision Damage' ? 'selected' : '' }}>Non-Collision Damage</option>
                <option value="Technical Damage" {{ $case->casetype == 'Technical Damage' ? 'selected' : '' }}>Technical Damage</option>
                <option value="Body Damage" {{ $case->casetype == 'Body Damage' ? 'selected' : '' }}>Body Damage</option>
                <option value="Glass Damage" {{ $case->casetype == 'Glass Damage' ? 'selected' : '' }}>Glass Damage</option>
                <option value="Total Loss Damage" {{ $case->casetype == 'Total Loss Damage' ? 'selected' : '' }}>Total Loss Damage</option>
            </select>
        </div>

        <div class="form-group mb-3">
            <label for="filledby" class="form-label">Filled By (Staff)</label>
            <input type="text" name="filledby" class="form-control reg-focus-ring"
                   value="{{ $case->filledby }}" readonly>
        </div>

        <div class="form-group mb-3">
            <label for="resolutionstatus" class="form-label">Resolution Status</label>
            <select name="resolutionstatus" class="form-control reg-focus-ring" required>
                <option value="Resolved" {{ $case->resolutionstatus == 'Resolved' ? 'selected' : '' }}>Resolved</option>
                <option value="Unresolved" {{ $case->resolutionstatus == 'Unresolved' ? 'selected' : '' }}>Unresolved</option>
            </select>
        </div>

        <button type="submit" class="btn reg-btn-primary">Update Damage Case</button>
    </form>
</div>
@endsection