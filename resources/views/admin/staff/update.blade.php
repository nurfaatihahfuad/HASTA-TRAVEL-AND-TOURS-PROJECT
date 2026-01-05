<!-- UPDATE staff info -->
@extends('layouts.it_admin')

@section('title', 'Edit Staff')

@section('content')
<div class="card shadow-sm border-0">
    <!-- Card Header -->
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">
            <i class="fas fa-user-edit me-2"></i> Edit Staff Information
        </h5>
    </div>

    <!-- Card Body -->
    <div class="card-body">
        <!-- Error Alert -->
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Success Alert -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Form update staff -->
        <form method="POST" action="{{ route('staff.update', $staff->userID) }}">
            @csrf
            @method('PUT')

            <!-- Row 1: Name + Email -->
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" name="name"
                           value="{{ old('name', $staff->name) }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email"
                           value="{{ old('email', $staff->email) }}" required>
                </div>
            </div>

            <!-- Row 2: Phone + IC -->
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="noHP" class="form-label">Phone</label>
                    <input type="text" class="form-control" id="noHP" name="noHP"
                           value="{{ old('noHP', $staff->noHP) }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="noIC" class="form-label">IC Number</label>
                    <input type="text" class="form-control" id="noIC" name="noIC"
                           value="{{ old('noIC', $staff->noIC) }}" required>
                </div>
            </div>

            <!-- Role -->
            <div class="mb-3">
                <label for="staffRole" class="form-label">Role</label>
                <select class="form-select" id="staffRole" name="staffRole" required>
                    <option value="salesperson" @selected(optional($staff->staff)->staffRole == 'salesperson')>
                        Salesperson
                    </option>
                    <option value="runner" @selected(optional($staff->staff)->staffRole == 'runner')>
                        Runner
                    </option>
                </select>
            </div>

            <!-- Action Buttons -->
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('staff.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Back
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i> Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
