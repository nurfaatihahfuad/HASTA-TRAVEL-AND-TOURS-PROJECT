@extends('layouts.salesperson')

@section('title', 'Edit Commission')

@section('content')
<div class="container">
    <h4 class="mb-3">Edit Commission</h4>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="commissionForm" action="{{ route('commission.update', $commission->commissionID) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="mb-3">
            <label class="form-label">Commission Type</label>
            <input type="text" name="commissionType" class="form-control" required 
                   value="{{ old('commissionType', $commission->commissionType) }}">
            @error('commissionType')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Applied Date</label>
            <input type="date" name="appliedDate" class="form-control" required
                   value="{{ old('appliedDate', $commission->appliedDate) }}">
            @error('appliedDate')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Amount (RM)</label>
            <input type="number" name="amount" class="form-control" required min="1"
                   value="{{ old('amount', $commission->amount) }}">
            @error('amount')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Bank Account Number</label>
            <input type="text" name="accountNumber" class="form-control" required 
                   value="{{ old('accountNumber', $commission->accountNumber) }}" maxlength="20">
            <small class="text-muted">Enter your bank account number (max 20 characters)</small>
            @error('accountNumber')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Bank Name</label>
            <select name="bankName" class="form-control" required id="bankSelect">
                <option value="">-- Please Select Bank --</option>
                
                @php
                    // Check jika bank name sedia ada dalam dropdown
                    $existingBankNames = [
                        'Maybank', 'CIMB Bank', 'Public Bank', 'RHB Bank', 'Hong Leong Bank', 
                        'AmBank', 'Affin Bank', 'Alliance Bank', 'Bank Islam', 'Bank Muamalat',
                        'Tabung Haji', 'Al Rajhi Bank', 'Kuwait Finance House', 'Bank Simpanan Nasional',
                        'Agrobank', 'MBSB', 'AEON Bank', 'GX Bank', 'Boost Bank', 'KAF Digital Bank',
                        'Boost eWallet', 'Touch and Go', 'Big Pay', 'Standard Chartered Bank',
                        'HSBC', 'Citibank', 'United Overseas Bank', 'OCBC', 'Bank of China',
                        'Bank of America', 'BNP Paribas', 'Bangkok Bank', 'China Construction Bank',
                        'Deutsche Bank', 'MUFG Bank', 'Mizuho Bank', 'ICBC', 'JP Morgan Chase',
                        'FassPay', 'Finexus Cards', 'Merchantrade', 'RYT Bank'
                    ];
                    
                    $currentBankName = old('bankName', $commission->bankName);
                    $isInList = in_array($currentBankName, $existingBankNames);
                @endphp
                
                <optgroup label="Local Conventional Banks">
                    <option value="Maybank" {{ (old('bankName', $commission->bankName) == 'Maybank') ? 'selected' : '' }}>Maybank</option>
                    <option value="CIMB Bank" {{ (old('bankName', $commission->bankName) == 'CIMB Bank') ? 'selected' : '' }}>CIMB Bank</option>
                    <option value="Public Bank" {{ (old('bankName', $commission->bankName) == 'Public Bank') ? 'selected' : '' }}>Public Bank</option>
                    <option value="RHB Bank" {{ (old('bankName', $commission->bankName) == 'RHB Bank') ? 'selected' : '' }}>RHB Bank</option>
                    <option value="Hong Leong Bank" {{ (old('bankName', $commission->bankName) == 'Hong Leong Bank') ? 'selected' : '' }}>Hong Leong Bank</option>
                    <option value="AmBank" {{ (old('bankName', $commission->bankName) == 'AmBank') ? 'selected' : '' }}>AmBank</option>
                    <option value="Affin Bank" {{ (old('bankName', $commission->bankName) == 'Affin Bank') ? 'selected' : '' }}>Affin Bank</option>
                    <option value="Alliance Bank" {{ (old('bankName', $commission->bankName) == 'Alliance Bank') ? 'selected' : '' }}>Alliance Bank</option>
                </optgroup>
                
                <optgroup label="Islamic Banks">
                    <option value="Bank Islam" {{ (old('bankName', $commission->bankName) == 'Bank Islam') ? 'selected' : '' }}>Bank Islam</option>
                    <option value="Bank Muamalat" {{ (old('bankName', $commission->bankName) == 'Bank Muamalat') ? 'selected' : '' }}>Bank Muamalat</option>
                    <option value="Tabung Haji" {{ (old('bankName', $commission->bankName) == 'Tabung Haji') ? 'selected' : '' }}>Tabung Haji</option>
                    <option value="Al Rajhi Bank" {{ (old('bankName', $commission->bankName) == 'Al Rajhi Bank') ? 'selected' : '' }}>Al Rajhi Bank</option>
                    <option value="Kuwait Finance House" {{ (old('bankName', $commission->bankName) == 'Kuwait Finance House') ? 'selected' : '' }}>Kuwait Finance House</option>
                </optgroup>
                
                <optgroup label="Government & Special Banks">
                    <option value="Bank Simpanan Nasional" {{ (old('bankName', $commission->bankName) == 'Bank Simpanan Nasional') ? 'selected' : '' }}>Bank Simpanan Nasional (BSN)</option>
                    <option value="Agrobank" {{ (old('bankName', $commission->bankName) == 'Agrobank') ? 'selected' : '' }}>Agrobank</option>
                    <option value="MBSB" {{ (old('bankName', $commission->bankName) == 'MBSB') ? 'selected' : '' }}>MBSB Bank</option>
                    <option value="AEON Bank" {{ (old('bankName', $commission->bankName) == 'AEON Bank') ? 'selected' : '' }}>AEON Bank</option>
                </optgroup>
                
                <optgroup label="Digital Banks & E-Wallets">
                    <option value="GX Bank" {{ (old('bankName', $commission->bankName) == 'GX Bank') ? 'selected' : '' }}>GX Bank</option>
                    <option value="Boost Bank" {{ (old('bankName', $commission->bankName) == 'Boost Bank') ? 'selected' : '' }}>Boost Bank</option>
                    <option value="KAF Digital Bank" {{ (old('bankName', $commission->bankName) == 'KAF Digital Bank') ? 'selected' : '' }}>KAF Digital Bank</option>
                    <option value="Boost eWallet" {{ (old('bankName', $commission->bankName) == 'Boost eWallet') ? 'selected' : '' }}>Boost eWallet</option>
                    <option value="Touch and Go" {{ (old('bankName', $commission->bankName) == 'Touch and Go') ? 'selected' : '' }}>Touch and Go eWallet</option>
                    <option value="Big Pay" {{ (old('bankName', $commission->bankName) == 'Big Pay') ? 'selected' : '' }}>Big Pay</option>
                </optgroup>
                
                <optgroup label="International Banks in Malaysia">
                    <option value="Standard Chartered Bank" {{ (old('bankName', $commission->bankName) == 'Standard Chartered Bank') ? 'selected' : '' }}>Standard Chartered Bank</option>
                    <option value="HSBC" {{ (old('bankName', $commission->bankName) == 'HSBC') ? 'selected' : '' }}>HSBC Bank</option>
                    <option value="Citibank" {{ (old('bankName', $commission->bankName) == 'Citibank') ? 'selected' : '' }}>Citibank</option>
                    <option value="United Overseas Bank" {{ (old('bankName', $commission->bankName) == 'United Overseas Bank') ? 'selected' : '' }}>United Overseas Bank (UOB)</option>
                    <option value="OCBC" {{ (old('bankName', $commission->bankName) == 'OCBC') ? 'selected' : '' }}>OCBC Bank</option>
                    <option value="Bank of China" {{ (old('bankName', $commission->bankName) == 'Bank of China') ? 'selected' : '' }}>Bank of China</option>
                    <option value="Bank of America" {{ (old('bankName', $commission->bankName) == 'Bank of America') ? 'selected' : '' }}>Bank of America</option>
                </optgroup>
                
                <optgroup label="Other International Banks">
                    <option value="BNP Paribas" {{ (old('bankName', $commission->bankName) == 'BNP Paribas') ? 'selected' : '' }}>BNP Paribas</option>
                    <option value="Bangkok Bank" {{ (old('bankName', $commission->bankName) == 'Bangkok Bank') ? 'selected' : '' }}>Bangkok Bank</option>
                    <option value="China Construction Bank" {{ (old('bankName', $commission->bankName) == 'China Construction Bank') ? 'selected' : '' }}>China Construction Bank</option>
                    <option value="Deutsche Bank" {{ (old('bankName', $commission->bankName) == 'Deutsche Bank') ? 'selected' : '' }}>Deutsche Bank</option>
                    <option value="MUFG Bank" {{ (old('bankName', $commission->bankName) == 'MUFG Bank') ? 'selected' : '' }}>MUFG Bank</option>
                    <option value="Mizuho Bank" {{ (old('bankName', $commission->bankName) == 'Mizuho Bank') ? 'selected' : '' }}>Mizuho Bank</option>
                    <option value="ICBC" {{ (old('bankName', $commission->bankName) == 'ICBC') ? 'selected' : '' }}>ICBC Bank</option>
                    <option value="JP Morgan Chase" {{ (old('bankName', $commission->bankName) == 'JP Morgan Chase') ? 'selected' : '' }}>JP Morgan Chase</option>
                </optgroup>
                
                <optgroup label="Payment & Fintech">
                    <option value="FassPay" {{ (old('bankName', $commission->bankName) == 'FassPay') ? 'selected' : '' }}>FassPay</option>
                    <option value="Finexus Cards" {{ (old('bankName', $commission->bankName) == 'Finexus Cards') ? 'selected' : '' }}>Finexus Cards</option>
                    <option value="Merchantrade" {{ (old('bankName', $commission->bankName) == 'Merchantrade') ? 'selected' : '' }}>Merchantrade</option>
                    <option value="RYT Bank" {{ (old('bankName', $commission->bankName) == 'RYT Bank') ? 'selected' : '' }}>RYT Bank</option>
                </optgroup>
                
                {{-- Jika bank name tidak dalam list, pilih "Other" dan set otherBankName --}}
                @if(!$isInList && $currentBankName)
                    <option value="Other" selected>Other ({{ $currentBankName }})</option>
                @else
                    <option value="Other" {{ old('bankName') == 'Other' ? 'selected' : '' }}>Other (Please specify)</option>
                @endif
            </select>
            <small class="text-muted">Select your bank for commission payment</small>
            @error('bankName')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <!-- Field untuk Other Bank (akan muncul jika pilih "Other") -->
        <div class="mb-3" id="otherBankField" style="display: {{ (!$isInList && $currentBankName) ? 'block' : 'none' }};">
            <label class="form-label">Specify Other Bank</label>
            <input type="text" name="otherBankName" class="form-control" 
                   value="{{ old('otherBankName', (!$isInList && $currentBankName) ? $currentBankName : '') }}" 
                   placeholder="Enter bank name" maxlength="100">
            @error('otherBankName')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <button type="button" id="submitBtn" class="btn btn-primary">Simpan</button>
        <button type="button" id="cancelBtn" class="btn btn-secondary">Batal</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const bankSelect = document.getElementById('bankSelect');
    const otherBankField = document.getElementById('otherBankField');
    
    // Handle "Other" bank selection
    if (bankSelect && otherBankField) {
        // Check initial value
        if (bankSelect.value === 'Other') {
            otherBankField.style.display = 'block';
        }
        
        // Listen for changes
        bankSelect.addEventListener('change', function() {
            if (this.value === 'Other') {
                otherBankField.style.display = 'block';
            } else {
                otherBankField.style.display = 'none';
            }
        });
    }
    
    // Submit button handler
    document.getElementById('submitBtn').addEventListener('click', function(e) {
        e.preventDefault();
        
        // Validasi semua fields
        const commissionType = document.querySelector('input[name="commissionType"]').value;
        const appliedDate = document.querySelector('input[name="appliedDate"]').value;
        const amount = document.querySelector('input[name="amount"]').value;
        const accountNumber = document.querySelector('input[name="accountNumber"]').value;
        const bankName = bankSelect ? bankSelect.value : '';
        
        // Check jika ada field kosong
        if (!commissionType.trim()) {
            Swal.fire({ icon: 'error', title: 'Ralat', text: 'Sila isi Commission Type!' });
            return;
        }
        if (!appliedDate) {
            Swal.fire({ icon: 'error', title: 'Ralat', text: 'Sila pilih Applied Date!' });
            return;
        }
        if (!amount || amount < 1) {
            Swal.fire({ icon: 'error', title: 'Ralat', text: 'Sila isi Amount dengan nilai 1 atau lebih!' });
            return;
        }
        if (!accountNumber.trim()) {
            Swal.fire({ icon: 'error', title: 'Ralat', text: 'Sila isi Bank Account Number!' });
            return;
        }
        if (!bankName) {
            Swal.fire({ icon: 'error', title: 'Ralat', text: 'Sila pilih Bank Name!' });
            return;
        }
        
        // Jika pilih "Other", validasi otherBankName
        if (bankName === 'Other') {
            const otherBankName = document.querySelector('input[name="otherBankName"]');
            if (!otherBankName || !otherBankName.value.trim()) {
                Swal.fire({ icon: 'error', title: 'Ralat', text: 'Sila isi nama bank untuk pilihan "Other"!' });
                return;
            }
        }
        
        // Get final bank name for display
        let displayBankName = bankName;
        if (bankName === 'Other') {
            const otherBankName = document.querySelector('input[name="otherBankName"]');
            displayBankName = otherBankName ? otherBankName.value : 'Other Bank';
        }
        
        // Show confirmation popup dengan semua details
        Swal.fire({
            title: 'Adakah anda pasti?',
            html: `
                <div class="text-start">
                    <p><strong>Commission Type:</strong> ${commissionType}</p>
                    <p><strong>Applied Date:</strong> ${appliedDate}</p>
                    <p><strong>Amount:</strong> RM ${amount}</p>
                    <p><strong>Bank Account:</strong> ${accountNumber}</p>
                    <p><strong>Bank Name:</strong> ${displayBankName}</p>
                    <div class="mt-2 text-warning">
                        <small><i class="fas fa-exclamation-triangle"></i> Status akan dikembalikan ke "Pending" selepas kemaskini.</small>
                    </div>
                </div>
            `,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Kemaskini!',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            width: '500px'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('commissionForm').submit();
            }
        });
    });
    
    // Cancel button handler
    document.getElementById('cancelBtn').addEventListener('click', function(e) {
        e.preventDefault();
        
        // Check jika ada perubahan dalam form
        const originalData = {
            commissionType: "{{ $commission->commissionType }}",
            appliedDate: "{{ $commission->appliedDate }}",
            amount: "{{ $commission->amount }}",
            accountNumber: "{{ $commission->accountNumber }}",
            bankName: "{{ $commission->bankName }}"
        };
        
        const currentData = {
            commissionType: document.querySelector('input[name="commissionType"]').value,
            appliedDate: document.querySelector('input[name="appliedDate"]').value,
            amount: document.querySelector('input[name="amount"]').value,
            accountNumber: document.querySelector('input[name="accountNumber"]').value,
            bankName: bankSelect ? bankSelect.value : ''
        };
        
        // Jika pilih "Other", check otherBankName juga
        if (currentData.bankName === 'Other') {
            const otherBankName = document.querySelector('input[name="otherBankName"]');
            currentData.bankName = otherBankName ? otherBankName.value : '';
        }
        
        // Check if any field has changed
        const hasChanges = 
            currentData.commissionType !== originalData.commissionType ||
            currentData.appliedDate !== originalData.appliedDate ||
            currentData.amount != originalData.amount ||
            currentData.accountNumber !== originalData.accountNumber ||
            currentData.bankName !== originalData.bankName;
        
        if (hasChanges) {
            Swal.fire({
                title: 'Adakah anda pasti?',
                text: "Semua perubahan yang belum disimpan akan hilang.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Batal',
                cancelButtonText: 'Kembali ke Edit',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "{{ route('commission.index') }}";
                }
            });
        } else {
            window.location.href = "{{ route('commission.index') }}";
        }
    });
});
</script>
@endsection