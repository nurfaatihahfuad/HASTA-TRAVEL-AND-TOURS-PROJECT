<!-- @extends('layouts.app')

@section('content')
<div class="container">
    <h2>Car Inspection Checklist</h2>

    <form action="{{ route('inspection.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label for="bookingID" class="form-label">Booking ID</label>
            <input type="text" name="bookingID" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="inspDate" class="form-label">Inspection Date</label>
            <input type="date" name="inspDate" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="carCondition" class="form-label">Car Condition</label>
            <textarea name="carCondition" class="form-control" required></textarea>
        </div>

        <div class="mb-3">
            <label for="mileageReturned" class="form-label">Mileage Returned</label>
            <input type="number" name="mileageReturned" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="fuelLevel" class="form-label">Fuel Level (%)</label>
            <input type="number" name="fuelLevel" class="form-control" required>
        </div>

       
        <div class="mb-3">
            <label class="form-label">Damage Detected</label><br>
            <input type="radio" name="damageDetected" value="yes"> Yes
            <input type="radio" name="damageDetected" value="no" checked> No
        </div>

        <div class="mb-3">
            <label for="remark" class="form-label">Remarks</label>
            <textarea name="remark" class="form-control"></textarea>
        </div>

        <div class="mb-3">
            <label for="evidence" class="form-label">Evidence Photo</label>
            <input type="file" name="evidence" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Submit Inspection</button>
    </form>
</div>
@endsection --> 

@section('content')
<style>
    /* CSS Variables - Scoped only to this page */
    :root {
        --reg-primary: #EC9A85;
        --reg-primary-dark: #D98B77;
        --reg-primary-light: #F9E0D9;
        --reg-primary-lightest: #FEF5F2;
    }

    body {
        background-image: url("/img/registration-bg.jpg");
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        background-repeat: no-repeat;
    }

    .min-h-screen { position: relative; z-index: 1; }

    .reg-bg-primary { background-color: var(--reg-primary) !important; }
    .reg-bg-primary-light { background-color: var(--reg-primary-light) !important; }
    .reg-text-primary { color: var(--reg-primary) !important; }
    .reg-text-primary-dark { color: var(--reg-primary-dark) !important; }
    .reg-border-primary-light { border-color: var(--reg-primary-light) !important; }

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

    .required:after { content: " *"; color: #ef4444; }
</style>

<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-3xl w-full bg-white rounded-lg shadow-xl overflow-hidden">
        
        <!-- Header -->
        <div class="reg-bg-primary text-white py-6 px-8">
            <h2 class="text-3xl font-bold text-center">Car Inspection Checklist</h2>
            <p class="text-center text-opacity-90 mt-2">Record inspection details and damage cases</p>
        </div>

        <!-- Form -->
        <div class="p-8">
            @if ($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6">
                    <p class="text-sm text-red-700 font-semibold">Please fix the following errors:</p>
                    <ul class="mt-2 list-disc list-inside text-sm text-red-700">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('success'))
                <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6">
                    <p class="text-sm text-green-700 font-semibold">{{ session('success') }}</p>
                </div>
            @endif

            <form action="{{ route('inspection.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <div>
                    <label for="bookingID" class="block text-gray-700 font-medium mb-2 required">Booking ID</label>
                    <input type="text" name="bookingID" id="bookingID" value="{{ old('bookingID') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg reg-focus-ring transition duration-300"
                           placeholder="Enter booking ID" required>
                </div>

                <div>
                    <label for="inspDate" class="block text-gray-700 font-medium mb-2 required">Inspection Date</label>
                    <input type="date" name="inspDate" id="inspDate" value="{{ old('inspDate') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg reg-focus-ring transition duration-300"
                           required>
                </div>

                <div>
                    <label for="carCondition" class="block text-gray-700 font-medium mb-2 required">Car Condition</label>
                    <textarea name="carCondition" id="carCondition"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg reg-focus-ring transition duration-300"
                              placeholder="Describe car condition" required>{{ old('carCondition') }}</textarea>
                </div>

                <div>
                    <label for="mileageReturned" class="block text-gray-700 font-medium mb-2 required">Mileage Returned</label>
                    <input type="number" name="mileageReturned" id="mileageReturned" value="{{ old('mileageReturned') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg reg-focus-ring transition duration-300"
                           placeholder="Enter mileage" required>
                </div>

                <div>
                    <label for="fuelLevel" class="block text-gray-700 font-medium mb-2 required">Fuel Level (%)</label>
                    <input type="number" name="fuelLevel" id="fuelLevel" value="{{ old('fuelLevel') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg reg-focus-ring transition duration-300"
                           placeholder="Enter fuel level" required>
                </div>

                <div>
                    <label class="block text-gray-700 font-medium mb-2">Damage Detected</label>
                    <div class="flex items-center space-x-4">
                        <label><input type="radio" name="damageDetected" value="yes" {{ old('damageDetected')=='yes'?'checked':'' }}> Yes</label>
                        <label><input type="radio" name="damageDetected" value="no" {{ old('damageDetected','no')=='no'?'checked':'' }}> No</label>
                    </div>
                </div>

                <div>
                    <label for="remark" class="block text-gray-700 font-medium mb-2">Remarks</label>
                    <textarea name="remark" id="remark"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg reg-focus-ring transition duration-300"
                              placeholder="Additional remarks">{{ old('remark') }}</textarea>
                </div>

                <div>
                    <label for="evidence" class="block text-gray-700 font-medium mb-2">Evidence Photo</label>
                    <input type="file" name="evidence" id="evidence"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg reg-focus-ring transition duration-300">
                </div>

                <button type="submit" class="reg-btn-primary px-6 py-3 rounded-lg font-semibold transition duration-300">
                    Submit Inspection
                </button>
            </form>
        </div>
    </div>
</div>
@endsection