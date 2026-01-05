<!--CREATE new admin-->
@extends('layouts.it_admin')

@section('title', 'Add New Admin')

@section('content')
<!-- Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1">Add New Administrator</h4>
        <p class="text-muted mb-0">Create a new admin account</p>
    </div>
    <a href="{{ route('admins.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i> Back to List
    </a>
</div>

<!-- Form Card -->
<div class="section-card">
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form method="POST" action="{{ route('admins.store') }}">
        @csrf
        
        <div class="row">
            <!-- Full Name -->
            <div class="col-md-6 mb-3">
                <label for="name" class="form-label">Full Name *</label>
                <input type="text" 
                       class="form-control @error('name') is-invalid @enderror" 
                       id="name" 
                       name="name" 
                       value="{{ old('name') }}" 
                       placeholder="Enter full name" 
                       required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <!-- Email -->
            <div class="col-md-6 mb-3">
                <label for="email" class="form-label">Email Address *</label>
                <input type="email" 
                       class="form-control @error('email') is-invalid @enderror" 
                       id="email" 
                       name="email" 
                       value="{{ old('email') }}" 
                       placeholder="Enter email address" 
                       required>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
        
        <div class="row">
            <!-- Phone Number -->
            <div class="col-md-6 mb-3">
                <label for="noHP" class="form-label">Phone Number *</label>
                <div class="input-group">
                    <span class="input-group-text">+60</span>
                    <input type="text" 
                           class="form-control @error('noHP') is-invalid @enderror" 
                           id="noHP" 
                           name="noHP" 
                           value="{{ old('noHP') }}" 
                           placeholder="e.g., 123456789" 
                           maxlength="10"
                           required>
                </div>
                @error('noHP')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>
            
            <!-- IC Number -->
            <div class="col-md-6 mb-3">
                <label for="noIC" class="form-label">IC Number *</label>
                <input type="text" 
                       class="form-control @error('noIC') is-invalid @enderror" 
                       id="noIC" 
                       name="noIC" 
                       value="{{ old('noIC') }}" 
                       placeholder="Enter 12-digit IC number" 
                       maxlength="12"
                       required>
                @error('noIC')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
        
        <div class="row">
            <!-- Password -->
            <div class="col-md-6 mb-3">
                <label for="password" class="form-label">Password *</label>
                <div class="input-group">
                    <input type="password" 
                           class="form-control @error('password') is-invalid @enderror" 
                           id="password" 
                           name="password" 
                           placeholder="Min. 8 characters" 
                           required>
                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password')">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
                @error('password')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>
            
            <!-- Confirm Password -->
            <div class="col-md-6 mb-3">
                <label for="password_confirmation" class="form-label">Confirm Password *</label>
                <div class="input-group">
                    <input type="password" 
                           class="form-control" 
                           id="password_confirmation" 
                           name="password_confirmation" 
                           placeholder="Confirm password" 
                           required>
                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password_confirmation')">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Admin Type Selection -->
        <div class="row mb-4">
            <div class="col-12">
                <label class="form-label">Admin Department *</label>
                <div class="row">
                    <!-- IT Card -->
                    <div class="col-md-6 mb-3">
                        <div class="card h-100 border @if(old('adminType') == 'IT') border-primary border-2 @elseif($errors->has('adminType')) border-danger @endif" 
                             style="cursor: pointer;"
                             onclick="selectRole('IT')">
                            <div class="card-body h-100">
                                <div class="form-check d-flex align-items-center mb-0">
                                    <input class="form-check-input me-3" 
                                           type="radio" 
                                           name="adminType" 
                                           id="roleIT" 
                                           value="IT"
                                           {{ old('adminType') == 'IT' ? 'checked' : '' }}
                                           required>
                                    <div class="d-flex align-items-center w-100">
                                        <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                                            <i class="fas fa-chart-line text-primary"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">IT Department</h6>
                                            <p class="text-muted small mb-0 mt-1">
                                                Oversees and manages the Hasta Vehicle Booking System
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Finance Card -->
                    <div class="col-md-6 mb-3">
                        <div class="card h-100 border @if(old('staffRole') == 'finance') border-primary border-2 @elseif($errors->has('adminType')) border-danger @endif" 
                             style="cursor: pointer;"
                             onclick="selectRole('finance')">
                            <div class="card-body h-100">
                                <div class="form-check d-flex align-items-center mb-0">
                                    <input class="form-check-input me-3" 
                                           type="radio" 
                                           name="adminType" 
                                           id="roleFinance" 
                                           value="finance"
                                           {{ old('adminType') == 'finance' ? 'checked' : '' }}>
                                    <div class="d-flex align-items-center w-100">
                                        <div class="bg-success bg-opacity-10 rounded-circle p-2 me-3">
                                            <i class="fas fa-truck text-success"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">Finance Department</h6>
                                            <p class="text-muted small mb-0 mt-1">
                                                Handles staff commissions and access revenue reporting
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @error('adminType')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>
        </div>
        
        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i>
            Admin ID will be automatically generated as <strong>UDXXXX</strong>.
        </div>
        
        <div class="mt-4 d-flex justify-content-center gap-3">
            <a href="{{ route('admins.index') }}" class="btn btn-outline-secondary px-4">
                <i class="fas fa-times me-2"></i> Cancel
            </a>
            <button type="submit" class="btn btn-primary px-4">
                <i class="fas fa-user-shield me-2"></i> Create Admin
            </button>
        </div>
    </form>
</div>

<script>
    function togglePassword(fieldId) {
        const field = document.getElementById(fieldId);
        const icon = field.nextElementSibling.querySelector('i');
        
        if (field.type === 'password') {
            field.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            field.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }

    function selectRole(role) {
        // Uncheck all radio buttons first
        document.querySelectorAll('input[name="adminType"]').forEach(radio => {
            radio.checked = false;
            // Remove border-primary from all cards
            radio.closest('.card').classList.remove('border-primary', 'border-2');
        });
        
        // Check the selected radio button
        const selectedRadio = document.getElementById('role' + role.charAt(0).toUpperCase() + role.slice(1));
        if (selectedRadio) {
            selectedRadio.checked = true;
            // Add border-primary to selected card
            selectedRadio.closest('.card').classList.add('border-primary', 'border-2');
        }
    }
    
    // Initialize border for already selected role
    document.addEventListener('DOMContentLoaded', function() {
        const selectedRole = document.querySelector('input[name="adminType"]:checked');
        if (selectedRole) {
            selectedRole.closest('.card').classList.add('border-primary', 'border-2');
        }
    });
    
    // Auto-numeric input for phone and IC
    document.getElementById('noHP').addEventListener('input', function(e) {
        this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);
    });
    
    document.getElementById('noIC').addEventListener('input', function(e) {
        this.value = this.value.replace(/[^0-9]/g, '').slice(0, 12);
    });
    
    // Form validation
    document.querySelector('form').addEventListener('submit', function(e) {
        // Check if role is selected
        const adminType = document.querySelector('input[name="adminType"]:checked');
        if (!adminType) {
            e.preventDefault();
            alert('Please select the admin department');
            return;
        }
        
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('password_confirmation').value;
        
        if (password !== confirmPassword) {
            e.preventDefault();
            alert('Passwords do not match');
            return;
        }
        
        if (password.length < 8) {
            e.preventDefault();
            alert('Password must be at least 8 characters');
            return;
        }
    });
</script>
@endsection