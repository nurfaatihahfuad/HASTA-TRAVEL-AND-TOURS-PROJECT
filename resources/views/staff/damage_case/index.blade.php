@extends('layouts.salesperson')

@section('title', 'Damage Cases')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-car-crash"></i> Damage Cases</h2>
        <a href="{{ route('staff.damage-cases.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> New Case
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            @if($damageCases->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Case ID</th>
                                <th>Type</th>
                                <th>Severity</th>
                                <th>Status</th>
                                <th>Filled By</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($damageCases as $case)
                            <tr>
                                <td>#{{ $case->caseID }}</td>
                                <td>{{ $case->casetype }}</td>
                                <td>
                                    <span class="badge bg-{{ $case->severity == 'High - Major damage' ? 'danger' : ($case->severity == 'Medium - Requires repair' ? 'warning' : 'success') }}">
                                        {{ $case->severity }}
                                    </span>
                                </td>
                                <td>{!! $case->status_badge !!}</td>
                                <td>{{ $case->filledby }}</td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('staff.damage-cases.show', $case->caseID) }}" 
                                           class="btn btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('staff.damage-cases.edit', $case->caseID) }}" 
                                           class="btn btn-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-3">
                    {{ $damageCases->links() }}
                </div>
            @else
                <div class="text-center py-4">
                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                    <h5>No damage cases found</h5>
                    <p class="text-muted">Start by creating your first damage case</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection