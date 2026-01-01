<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Registration</title>
    
    <!-- Vite for Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        /* CSS Variables - Scoped only to this page */
        :root {
            --reg-primary: #EC9A85;
            --reg-primary-dark: #D98B77;
            --reg-primary-light: #F9E0D9;
            --reg-primary-lightest: #FEF5F2;
        }
        
        /* Registration-specific utility classes */
        .reg-bg-primary {
            background-color: var(--reg-primary) !important;
        }
        
        .reg-bg-primary-light {
            background-color: var(--reg-primary-light) !important;
        }
        
        .reg-text-primary {
            color: var(--reg-primary) !important;
        }
        
        .reg-text-primary-dark {
            color: var(--reg-primary-dark) !important;
        }
        
        .reg-border-primary-light {
            border-color: var(--reg-primary-light) !important;
        }
        
        .reg-btn-primary {
            background-color: var(--reg-primary) !important;
            border-color: var(--reg-primary) !important;
            color: white !important;
        }
        
        .reg-btn-primary:hover {
            background-color: var(--reg-primary-dark) !important;
            border-color: var(--reg-primary-dark) !important;
        }
        
        .reg-btn-primary:focus {
            box-shadow: 0 0 0 4px rgba(236, 154, 133, 0.25) !important;
        }
        
        .reg-focus-ring:focus {
            border-color: var(--reg-primary) !important;
            box-shadow: 0 0 0 2px rgba(236, 154, 133, 0.25) !important;
            outline: none !important;
        }
        
        .reg-checkbox:checked {
            background-color: var(--reg-primary) !important;
            border-color: var(--reg-primary) !important;
        }
        
        .reg-checkbox:focus {
            box-shadow: 0 0 0 2px rgba(236, 154, 133, 0.25) !important;
        }
        
        .reg-tab-active {
            background-color: var(--reg-primary) !important;
            color: white !important;
        }
        
        .required:after {
            content: " *";
            color: #ef4444;
        }
        
        .customer-fields {
            transition: all 0.3s ease;
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl w-full bg-white rounded-lg shadow-xl overflow-hidden">
            <!-- Header -->
            <div class="reg-bg-primary text-white py-6 px-8">
                <h2 class="text-3xl font-bold text-center">Customer Registration</h2>
                <p class="text-center text-opacity-90 mt-2">Create your customer account</p>
            </div>

            <!-- Form -->
            <div class="p-8">
                @if ($errors->any())
                    <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-circle text-red-500"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-red-700">
                                    Please fix the following errors:
                                </p>
                                <ul class="mt-2 list-disc list-inside text-sm text-red-700">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                @if (session('success'))
                    <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-check-circle text-green-500"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-green-700">{{ session('success') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Update the form opening tag to store in multiple tables-->
                <form method="POST" action="{{ route('customer.register.store') }}" enctype="multipart/form-data" class="space-y-8">

                    @csrf

                    <!-- Customer Type Selection -->
                    <div class="border-b border-gray-200">
                        <div class="flex space-x-4 mb-6">
                            <button type="button" 
                                    onclick="selectCustomerType('student')" 
                                    id="studentTab" 
                                    class="tab-btn px-6 py-3 rounded-lg font-medium transition-all duration-300 reg-tab-active">
                                <i class="fas fa-graduation-cap mr-2"></i>Student Customer
                            </button>
                            <button type="button" 
                                    onclick="selectCustomerType('staff')" 
                                    id="staffTab" 
                                    class="tab-btn px-6 py-3 rounded-lg font-medium transition-all duration-300 bg-gray-100 text-gray-700 hover:bg-gray-200">
                                <i class="fas fa-briefcase mr-2"></i>Staff Customer
                            </button>
                        </div>
                        
                        <input type="hidden" name="customerType" id="customerType" value="student">
                    </div>

                    <!-- Basic Information Section -->
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-user-circle mr-2 reg-text-primary"></i> Basic Information
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Full Name -->
                            <div>
                                <label for="name" class="block text-gray-700 font-medium mb-2 required">Full Name</label>
                                <input type="text" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name') }}"
                                       required
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg reg-focus-ring transition duration-300"
                                       placeholder="Enter your full name">
                            </div>

                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-gray-700 font-medium mb-2 required">Email Address</label>
                                <input type="email" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email') }}"
                                       required
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg reg-focus-ring transition duration-300"
                                       placeholder="Enter your email">
                            </div>

                            <!-- Phone Number -->
                            <div>
                                <label for="noHP" class="block text-gray-700 font-medium mb-2 required">Phone Number</label>
                                <div class="flex">
                                    <span class="inline-flex items-center px-3 rounded-l-lg border border-r-0 border-gray-300 bg-gray-50 text-gray-500">
                                        +60
                                    </span>
                                    <input type="text" 
                                           id="noHP" 
                                           name="noHP" 
                                           value="{{ old('noHP') }}"
                                           required
                                           class="w-full px-4 py-3 border border-gray-300 rounded-r-lg reg-focus-ring transition duration-300"
                                           placeholder="123456789">
                                </div>
                            </div>

                            <!-- IC Number -->
                            <div>
                                <label for="noIC" class="block text-gray-700 font-medium mb-2 required">IC Number</label>
                                <input type="text" 
                                       id="noIC" 
                                       name="noIC" 
                                       value="{{ old('noIC') }}"
                                       required
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg reg-focus-ring transition duration-300"
                                       placeholder="e.g., 901231011234">
                            </div>

                            <!-- Password -->
                            <div>
                                <label for="password" class="block text-gray-700 font-medium mb-2 required">Password</label>
                                <div class="relative">
                                    <input type="password" 
                                           id="password" 
                                           name="password" 
                                           required
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg reg-focus-ring transition duration-300"
                                           placeholder="Minimum 8 characters">
                                    <button type="button" 
                                            onclick="togglePassword('password')"
                                            class="absolute right-3 top-3 text-gray-500 hover:text-gray-700">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <p class="mt-1 text-sm text-gray-500">Must be at least 8 characters</p>
                            </div>

                            <!-- Confirm Password -->
                            <div>
                                <label for="password_confirmation" class="block text-gray-700 font-medium mb-2 required">Confirm Password</label>
                                <div class="relative">
                                    <input type="password" 
                                           id="password_confirmation" 
                                           name="password_confirmation" 
                                           required
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg reg-focus-ring transition duration-300"
                                           placeholder="Confirm your password">
                                    <button type="button" 
                                            onclick="togglePassword('password_confirmation')"
                                            class="absolute right-3 top-3 text-gray-500 hover:text-gray-700">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Document Uploads Section -->
                        <div class="mt-8 pt-6 border-t border-gray-200">
                            <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                                <i class="fas fa-file-upload mr-2 reg-text-primary"></i> Required Documents
                            </h4>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- IC Copy Upload -->
                                <div>
                                    <label class="block text-gray-700 font-medium mb-2 required">IC Copy (Front & Back)</label>
                                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center bg-gray-50 hover:bg-gray-100 transition duration-300 cursor-pointer" 
                                         id="icUploadContainer"
                                         onclick="document.getElementById('ic').click()">
                                        <input type="file" 
                                               id="ic" 
                                               name="ic" 
                                               accept=".jpg,.jpeg,.png,.pdf"
                                               class="hidden"
                                               onchange="handleFileUpload(this, 'icPreview', 'icFileName')">
                                        <div class="flex flex-col items-center justify-center">
                                            <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-2"></i>
                                            <p class="text-sm text-gray-600">
                                                <span class="font-medium reg-text-primary">Click to upload</span> or drag and drop
                                            </p>
                                            <p class="text-xs text-gray-500 mt-1">JPG, PNG, or PDF (Max: 5MB)</p>
                                            <p class="text-xs text-gray-500">Upload both front and back in one file</p>
                                        </div>
                                    </div>
                                    <div id="icPreview" class="mt-3 hidden">
                                        <img id="icPreviewImage" class="max-w-40 max-h-40 rounded-lg border border-gray-300 hidden">
                                        <div id="icFileName" class="bg-gray-100 rounded-lg p-3 mt-2">
                                            <div class="flex justify-between items-center">
                                                <span class="text-sm font-medium text-gray-700"></span>
                                                <button type="button" 
                                                        onclick="removeFile('ic', 'icPreview', 'icFileName')"
                                                        class="text-red-500 hover:text-red-700">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Driver's License Upload -->
                                <div>
                                    <label class="block text-gray-700 font-medium mb-2 required">Driver's License Copy</label>
                                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center bg-gray-50 hover:bg-gray-100 transition duration-300 cursor-pointer" 
                                         id="licenseUploadContainer"
                                         onclick="document.getElementById('drivers_license').click()">
                                        <input type="file" 
                                               id="drivers_license" 
                                               name="drivers_license" 
                                               accept=".jpg,.jpeg,.png,.pdf"
                                               class="hidden"
                                               onchange="handleFileUpload(this, 'licensePreview', 'licenseFileName')">
                                        <div class="flex flex-col items-center justify-center">
                                            <i class="fas fa-id-card text-3xl text-gray-400 mb-2"></i>
                                            <p class="text-sm text-gray-600">
                                                <span class="font-medium reg-text-primary">Click to upload</span> or drag and drop
                                            </p>
                                            <p class="text-xs text-gray-500 mt-1">JPG, PNG, or PDF (Max: 5MB)</p>
                                        </div>
                                    </div>
                                    <div id="licensePreview" class="mt-3 hidden">
                                        <img id="licensePreviewImage" class="max-w-40 max-h-40 rounded-lg border border-gray-300 hidden">
                                        <div id="licenseFileName" class="bg-gray-100 rounded-lg p-3 mt-2">
                                            <div class="flex justify-between items-center">
                                                <span class="text-sm font-medium text-gray-700"></span>
                                                <button type="button" 
                                                        onclick="removeFile('drivers_license', 'licensePreview', 'licenseFileName')"
                                                        class="text-red-500 hover:text-red-700">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> <!-- Closing div for Basic Information -->

                    <!-- Banking Information Section -->
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-university mr-2 reg-text-primary"></i> Banking Information
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Account Number -->
                            <div>
                                <label for="accountNumber" class="block text-gray-700 font-medium mb-2 required">Account Number</label>
                                <input type="text" 
                                       id="accountNumber" 
                                       name="accountNumber" 
                                       value="{{ old('accountNumber') }}"
                                       required
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg reg-focus-ring transition duration-300"
                                       placeholder="Enter your bank account number">
                            </div>

                            <!-- Bank Type -->
                            <div>
                                <label for="bankType" class="block text-gray-700 font-medium mb-2 required">Bank Type</label>
                                <select id="bankType" 
                                        name="bankType" 
                                        required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg reg-focus-ring transition duration-300">
                                    <option value="">Select Bank</option>
                                    @foreach($bankTypes as $bankType)
                                        <option value="{{ $bankType }}" {{ old('bankType') == $bankType ? 'selected' : '' }}>
                                            {{ $bankType }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Referral Code (Optional) -->
                            <div class="md:col-span-2">
                                <label for="referralCode" class="block text-gray-700 font-medium mb-2">Referral Code (Optional)</label>
                                <input type="text" 
                                       id="referralCode" 
                                       name="referralCode" 
                                       value="{{ old('referralCode') }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg reg-focus-ring transition duration-300"
                                       placeholder="Enter referral code if any">
                                <p class="mt-1 text-sm text-gray-500">If you have a referral code from an existing customer</p>
                            </div>
                        </div>
                    </div>

                    <!-- Student Customer Fields -->
                    <div id="studentFields" class="customer-fields reg-bg-primary-light p-6 rounded-lg reg-border-primary-light border">
                        <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-graduation-cap mr-2 reg-text-primary-dark"></i> Student Information
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Matric Number -->
                            <div>
                                <label for="matricNo" class="block text-gray-700 font-medium mb-2 required">Matric Number</label>
                                <input type="text" 
                                       id="matricNo" 
                                       name="matricNo" 
                                       value="{{ old('matricNo') }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg reg-focus-ring transition duration-300"
                                       placeholder="e.g., A12345">
                            </div>


                            <!-- Matric Card Upload -->
                            <div class="md:col-span-2">
                                <label class="block text-gray-700 font-medium mb-2 required">Matric Card Copy</label>
                                <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center bg-gray-50 hover:bg-gray-100 transition duration-300 cursor-pointer" 
                                     id="matricUploadContainer"
                                     onclick="document.getElementById('matric_card').click()">
                                    <input type="file" 
                                           id="matric_card" 
                                           name="matric_card" 
                                           accept=".jpg,.jpeg,.png,.pdf"
                                           class="hidden"
                                           onchange="handleFileUpload(this, 'matricPreview', 'matricFileName')">
                                    <div class="flex flex-col items-center justify-center">
                                        <i class="fas fa-id-badge text-3xl text-gray-400 mb-2"></i>
                                        <p class="text-sm text-gray-600">
                                            <span class="font-medium reg-text-primary">Click to upload</span> or drag and drop
                                        </p>
                                        <p class="text-xs text-gray-500 mt-1">JPG, PNG, or PDF (Max: 5MB)</p>
                                        <p class="text-xs text-gray-500">Clear photo of your matric card</p>
                                    </div>
                                </div>
                                <div id="matricPreview" class="mt-3 hidden">
                                    <img id="matricPreviewImage" class="max-w-40 max-h-40 rounded-lg border border-gray-300 hidden">
                                    <div id="matricFileName" class="bg-gray-100 rounded-lg p-3 mt-2">
                                        <div class="flex justify-between items-center">
                                            <span class="text-sm font-medium text-gray-700"></span>
                                            <button type="button" 
                                                    onclick="removeFile('matric_card', 'matricPreview', 'matricFileName')"
                                                    class="text-red-500 hover:text-red-700">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Faculty - Connected to Database -->
                            <div>
                                <label for="facultyID" class="block text-gray-700 font-medium mb-2 required">Faculty</label>
                                <select id="facultyID" 
                                        name="facultyID" 
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg reg-focus-ring transition duration-300">
                                    <option value="">Select Faculty</option>
                                    @foreach($faculties as $faculty)
                                        <option value="{{ $faculty->facultyID }}" {{ old('facultyID') == $faculty->facultyID ? 'selected' : '' }}>
                                            {{ $faculty->facultyName }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Residential College - Connected to Database -->
                            <div>
                                <label for="collegeID" class="block text-gray-700 font-medium mb-2 required">Residential College</label>
                                <select id="collegeID" 
                                        name="collegeID" 
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg reg-focus-ring transition duration-300">
                                    <option value="">Select Residential College</option>
                                    @foreach($residentialColleges as $college)
                                        <option value="{{ $college->collegeID }}" {{ old('collegeID') == $college->collegeID ? 'selected' : '' }}>
                                            {{ $college->collegeName }}
                                        </option>
                                    @endforeach
                                </select>
                            </div> 
                        </div>
                    </div>

                    <!-- Staff Customer Fields -->
                    <div id="staffFields" class="customer-fields hidden bg-purple-50 p-6 rounded-lg border border-purple-200">
                        <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-briefcase mr-2 text-purple-600"></i> Staff Information
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Staff Number -->
                            <div>
                                <label for="staffNo" class="block text-gray-700 font-medium mb-2 required">Staff Number</label>
                                <input type="text" 
                                       id="staffNo" 
                                       name="staffNo" 
                                       value="{{ old('staffNo') }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg reg-focus-ring transition duration-300"
                                       placeholder="e.g., STF12345">
                            </div>
                        </div>
                    </div>


                    <!-- Submit Button -->
                    <div>
                        <button type="submit" 
                                class="w-full reg-btn-primary font-bold py-4 px-4 rounded-lg transition duration-300 flex items-center justify-center">
                            <i class="fas fa-user-plus mr-2"></i> Register Now
                        </button>
                    </div>
                    
                    <!-- Login Link (Optional) -->
                    <div class="text-center pt-4">
                        <p class="text-gray-600">
                            Already have an account?
                            <a href="{{ route('login') }}" class="reg-text-primary font-semibold hover:underline ml-1">
                                Sign in here
                            </a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Toggle between student and staff tabs
        function selectCustomerType(type) {
            // Update hidden input
            document.getElementById('customerType').value = type;
            
            // Update tab styles
            const tabs = document.querySelectorAll('.tab-btn');
            tabs.forEach(tab => {
                if (tab.id === type + 'Tab') {
                    tab.classList.add('reg-tab-active', 'reg-bg-primary', 'text-white');
                    tab.classList.remove('bg-gray-100', 'text-gray-700', 'hover:bg-gray-200');
                } else {
                    tab.classList.remove('reg-tab-active', 'reg-bg-primary', 'text-white');
                    tab.classList.add('bg-gray-100', 'text-gray-700', 'hover:bg-gray-200');
                }
            });
            
            // Show/hide fields
            if (type === 'student') {
                document.getElementById('studentFields').classList.remove('hidden');
                document.getElementById('staffFields').classList.add('hidden');
                
                // Set required for student fields
                document.getElementById('matricNo').required = true;
                document.getElementById('facultyID').required = true;
                document.getElementById('collegeID').required = true;
                
                // Remove required from staff fields
                document.getElementById('staffNo').required = false;
            } else {
                document.getElementById('studentFields').classList.add('hidden');
                document.getElementById('staffFields').classList.remove('hidden');
                
                // Set required for staff fields
                document.getElementById('staffNo').required = true;
                
                // Remove required from student fields
                document.getElementById('matricNo').required = false;
                document.getElementById('facultyID').required = false;
                document.getElementById('collegeID').required = false;
            }
        }
        
        // Toggle password visibility
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const type = field.getAttribute('type') === 'password' ? 'text' : 'password';
            field.setAttribute('type', type);
            
            // Update eye icon
            const icon = field.nextElementSibling.querySelector('i');
            if (type === 'text') {
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
        
        // Form validation before submission
        document.querySelector('form').addEventListener('submit', function(e) {
            const customerType = document.getElementById('customerType').value;
            let errorMsg = [];
            
            if (customerType === 'student') {
                const requiredFields = ['matricNo', 'facultyID', 'collegeID'];
                for (let field of requiredFields) {
                    const element = document.getElementById(field);
                    if (element && !element.value.trim()) {
                        let fieldName = field.replace('_id', '').replace(/([A-Z])/g, ' $1').toLowerCase();
                        if (field === 'matricNo') fieldName = 'matric number';
                        if (field === 'facultyID') fieldName = 'faculty';
                        if (field === 'collegeID') fieldName = 'residential college';

                        errorMsg.push('Please fill in ${fieldName}');
                    }
                    /*if (!document.getElementById(field).value.trim()) {
                        e.preventDefault();
                        alert(`Please fill in all student information fields.`);
                        document.getElementById(field).focus();
                        return;
                    }*/
                }
                // check file uploads
                const fileFields = ['ic', 'drivers_license', 'matric_card'];
                for (let field of fileFields) {
                    const element = document.getElementById(field);
                    if (element && (!element.files || element.files.length === 0)) {
                        errorMessages.push(`Please upload ${field.replace('_', ' ')}`);
                        if (errorMessages.length === 1) {
                            element.closest('.border-dashed').scrollIntoView({ behavior: 'smooth' });
                        }
                    }
                }
            } else {
                const requiredFields = ['staffNo'];
                for (let field of requiredFields) {
                    if (!document.getElementById(field).value.trim()) {
                        e.preventDefault();
                        alert(`Please fill in all staff information fields.`);
                        document.getElementById(field).focus();
                        return;
                    }
                }
            }
        });
        
        // Initialize based on old input or default
        document.addEventListener('DOMContentLoaded', function() {
            const oldType = "{{ old('customerType', 'student') }}";
            selectCustomerType(oldType);
            
            // Initialize all password toggle icons
            const passwordFields = document.querySelectorAll('input[type="password"]');
            passwordFields.forEach(field => {
                const icon = field.nextElementSibling.querySelector('i');
                icon.classList.add('fa-eye');
            });
        });

        // File upload handling
        function handleFileUpload(input, previewId, fileNameId) {
            const file = input.files[0];
            if (!file) return;
            
            // Validate file size (5MB)
            if (file.size > 5 * 1024 * 1024) {
                alert('File size must be less than 5MB');
                input.value = '';
                return;
            }
            
            // Validate file type
            const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'application/pdf'];
            if (!validTypes.includes(file.type)) {
                alert('Please upload a JPG, PNG, or PDF file');
                input.value = '';
                return;
            }
            
            // Show preview
            const previewContainer = document.getElementById(previewId);
            const fileNameContainer = document.getElementById(fileNameId);
            
            previewContainer.classList.remove('hidden');
            
            // Format file size - THIS IS THE NEW PART
            const fileSize = (file.size / 1024 / 1024).toFixed(2);
            
            // Update file info - UPDATED TO SHOW FILE SIZE
            fileNameContainer.querySelector('span').innerHTML = `
                ${file.name}<br>
                <span class="text-xs text-gray-500">Size: ${fileSize} MB</span>
            `;
            
            // Show image preview for image files
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const previewImage = document.getElementById(previewId.replace('Preview', 'PreviewImage'));
                    previewImage.src = e.target.result;
                    previewImage.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            }
        }

        // Add these missing functions:
        function removeFile(inputId, previewId, fileNameId) {
            document.getElementById(inputId).value = '';
            document.getElementById(previewId).classList.add('hidden');
            document.getElementById(fileNameId).querySelector('span').textContent = '';
            
            // Hide image preview if exists
            const previewImage = document.getElementById(previewId.replace('Preview', 'PreviewImage'));
            if (previewImage) {
                previewImage.classList.add('hidden');
                previewImage.src = '';
            }
        }
        
        function setupDragAndDrop(dropZoneId, inputId) {
            const dropZone = document.getElementById(dropZoneId);
            const fileInput = document.getElementById(inputId);
            
            dropZone.addEventListener('dragover', function(e) {
                e.preventDefault();
                dropZone.classList.add('border-reg-primary', 'bg-reg-primary-lightest');
            });
            
            dropZone.addEventListener('dragleave', function(e) {
                dropZone.classList.remove('border-reg-primary', 'bg-reg-primary-lightest');
            });
            
            dropZone.addEventListener('drop', function(e) {
                e.preventDefault();
                dropZone.classList.remove('border-reg-primary', 'bg-reg-primary-lightest');
                
                if (e.dataTransfer.files.length) {
                    fileInput.files = e.dataTransfer.files;
                    // Trigger change event
                    const event = new Event('change', { bubbles: true });
                    fileInput.dispatchEvent(event);
                }
            });
        }

    </script>
</body>
</html>