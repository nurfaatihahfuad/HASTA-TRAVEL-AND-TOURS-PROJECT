<!-- UPDATE admin info -->
@extends('layouts.it_admin')

@section('title', 'Edit Admin')

@section('content')
<div class="card shadow-sm border-0">
    <!-- Card Header -->
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">
            <i class="fas fa-user-shield me-2"></i> Edit Admin Information
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

        <!-- Form update admin -->
        <form method="POST" action="{{ route('admins.update', $admins->userID) }}">
            @csrf
            @method('PUT')

            <!-- Row 1: Name + Email -->
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" name="name"
                           value="{{ old('name', $admins->name) }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email"
                           value="{{ old('email', $admins->email) }}" required>
                </div>
            </div>

            <!-- Row 2: Phone + IC -->
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="noHP" class="form-label">Phone</label>
                    <input type="text" class="form-control" id="noHP" name="noHP"
                           value="{{ old('noHP', $admins->noHP) }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="noIC" class="form-label">IC Number</label>
                    <input type="text" class="form-control" id="noIC" name="noIC"
                           value="{{ old('noIC', $admins->noIC) }}" required>
                </div>
            </div>

            <!-- Department Role -->
            <div class="mb-3">
                <label for="adminType" class="form-label">Department</label>
                <select class="form-select" id="adminType" name="adminType" required>
                    <option value="IT" @selected(optional($admins->admin)->adminType == 'IT')>IT Admin</option>
                    <option value="finance" @selected(optional($admins->admin)->adminType == 'finance')>Finance Admin</option>
                </select>
            </div>

            <!-- Action Buttons -->
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('admins.index') }}" class="btn btn-secondary">
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
