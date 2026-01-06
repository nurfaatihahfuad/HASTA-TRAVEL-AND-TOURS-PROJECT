@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Damage Case</h2>

    <!-- Form create damage case -->
    <form action="{{ route('damage.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="inspectionID" class="form-label">Inspection ID</label>
            <select name="inspectionID" class="form-control" required>
                <option value="">Select inspection ID</option>
                @foreach($inspections as $inspection)
                    <option value="{{ $inspection->inspectionID }}">{{ $inspection->inspectionID }}</option>
                @endforeach
            </select>
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

        <button type="submit" class="btn btn-danger">Create Damage Case</button>
    </form>

    <hr>

    <!-- Senarai damage case -->
    <h3>Existing Damage Cases</h3>
    <table class="table">
        <thead>
            <tr>
                <th>Case ID</th>
                <th>Type</th>
                <th>Filled By</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($cases as $case)
            <tr>
                <td>{{ $case->caseID }}</td>
                <td>{{ $case->casetype }}</td>
                <td>{{ $case->filledby }}</td>
                <td>{{ $case->resolutionstatus }}</td>
                <td>
                    @if($case->resolutionstatus == 'Unresolved')
                        <form action="{{ route('damage.resolve', $case->caseID) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success">Resolve</button>
                        </form>
                    @else
                        <span class="badge bg-success">Resolved</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection