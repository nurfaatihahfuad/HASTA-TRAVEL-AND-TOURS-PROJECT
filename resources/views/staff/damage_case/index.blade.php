@extends('layouts.salesperson')
@section('title', 'Damage Case List')
@section('content')
<div class="container">
    <h2>Damage Case List</h2>
    <a href="{{ route('damagecase.create') }}" class="btn btn-outline-secondary px-4">+ New Damage Case</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Case ID</th>
                <th>Inspection</th>
                <th>Case Type</th>
                <th>Filled By</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($cases as $case)
                <tr>
                    <td>{{ $case->caseID }}</td>
                    <td>{{ $case->inspectionID }}</td>
                    <td>{{ $case->casetype }}</td>
                    <td>{{ $case->filledby }}</td>
                    <td>{{ $case->resolutionstatus }}</td>
                    <td>
                        <!-- Update button -->
                        <a href="{{ route('damagecase.edit', ['damagecase' => $case->caseID]) }}" 
                           class="btn btn-sm btn-warning">
                            Update
                        </a>

                        <!-- Delete button -->
                        <form action="{{ route('damagecase.destroy', ['damagecase' => $case->caseID]) }}" 
                              method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger"
                                    onclick="return confirm('Are you sure you want to delete this case?')">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">No damage cases found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection