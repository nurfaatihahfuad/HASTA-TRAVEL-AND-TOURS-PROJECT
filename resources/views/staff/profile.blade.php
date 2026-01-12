@extends('layouts.salesperson')
@section('title', 'My Profile')

@push('styles')
<style>
    .card-body {
        border: none;
        border-radius: 14px;
        box-shadow: 0 6px 18px rgba(0,0,0,0.06);
    }
    </style>
@endpush

@section('content')
<div class="container-fluid">

    {{-- Page Header --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h4 class="fw-semibold mb-0">My Profile</h4>
    </div>

    {{-- Alerts --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row g-4">

        {{-- Left: Profile Card --}}
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body text-center">

                    {{-- Avatar --}}
                    <div class="mb-3">
                        <div class="rounded-circle bg-danger text-white d-flex align-items-center justify-content-center mx-auto"
                             style="width:90px;height:90px;font-size:32px;">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    </div>

                    <h5 class="fw-semibold mb-1">{{ $user->name }}</h5>
                    <p class="text-muted mb-3">{{ $user->email }}</p>

                    <div class="text-start small">
                        <p class="mb-1"><strong>Phone:</strong> +60 {{ $user->noHP }}</p>
                        <p class="mb-0"><strong>IC:</strong> {{ $user->noIC }}</p>
                    </div>

                </div>
            </div>
        </div>

        {{-- Right: Edit Form --}}
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-body">

                    <form method="POST" action="{{ route('salesperson.profile.update') }}">
                        @csrf

                        {{-- Personal Info --}}
                        <h6 class="fw-semibold mb-3">Personal Information</h6>

                        <div class="row g-3 mb-4">

                            <div class="col-md-6">
                                <label class="form-label">Full Name</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                    <input type="text"
                                           name="name"
                                           class="form-control"
                                           value="{{ old('name', $user->name) }}"
                                           required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                    <input class="form-control" disabled value="{{ $user->email }}">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Phone Number</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                    <input type="text"
                                           name="noHP"
                                           class="form-control"
                                           value="{{ old('noHP', $user->noHP) }}"
                                           required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">IC Number</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                                    <input class="form-control" disabled value="{{ $user->noIC }}">
                                </div>
                            </div>

                        </div>

                        <hr>

                        {{-- Security --}}
                        <h6 class="fw-semibold mb-3">Security</h6>

                        <div class="row g-3">

                            <div class="col-md-6">
                                <label class="form-label">New Password</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    <input type="password"
                                           name="password"
                                           class="form-control"
                                           placeholder="Leave blank to keep current">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Confirm Password</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    <input type="password"
                                           name="password_confirmation"
                                           class="form-control">
                                </div>
                            </div>

                        </div>

                        {{-- Actions --}}
                        <div class="mt-4 text-end">
                            <button class="btn btn-danger px-4">
                                <i class="fas fa-save me-1"></i> Save Changes
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        </div>

    </div>

</div>
@endsection
