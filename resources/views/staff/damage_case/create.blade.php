@extends('layouts.salesperson')
@section('title', 'Add Damage Case')
@section('content')
<div class="container">
    <h2>Create Damage Case</h2>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('damagecase.store') }}" method="POST">
        @csrf

        {{-- Inspection ID auto-tally, hidden field --}}
        <div class="mb-3">
            <label class="form-label">Inspection ID</label>
            <input type="hidden" name="inspectionID" value="{{ $inspection->inspectionID }}">
            <p>{{ $inspection->inspectionID }}</p>
        </div>

        <div class="mb-3">
            <label for="casetype" class="form-label">Case Type</label>
            <select name="casetype" class="form-control" required>
                <option value="">Select damage case type</option>
                <option value="Collision Damage">Collision Damage</option>
                <option value="Non-Collision Damage">Non-Collision Damage</option>
                <option value="Technical Damage">Technical Damage</option>
                <option value="Body Damage">Body Damage</option>
                <option value="Glass Damage">Glass Damage</option>
                <option value="Total Loss Damage">Total Loss Damage</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="filledby" class="form-label">Filled By (Staff)</label>
            <input type="text" name="filledby" class="form-control" value="{{ Auth::user()->name }}" readonly>
        </div>

        <div class="mb-3">
            <label for="resolutionstatus" class="form-label">Resolution Status</label>
            <select name="resolutionstatus" class="form-control" required>
                <option value="">Select resolution status</option>
                <option value="Resolved">Resolved</option>
                <option value="Unresolved">Unresolved</option>
            </select>
        </div>

        <button type="submit" class="btn btn-outline-secondary px-4">Create Damage Case</button>
    </form>
</div>
@endsection