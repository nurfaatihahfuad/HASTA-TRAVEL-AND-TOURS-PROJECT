@extends('layouts.salesperson')

@section('title', 'Add Commission')

@section('content')
<div class="container">
    <h4 class="mb-3">New Commission</h4>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="commissionForm" action="{{ route('commission.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="mb-3">
            <label class="form-label">Commission Type</label>
            <input type="text" name="commissionType" class="form-control" required 
                   value="{{ old('commissionType') }}">
            @error('commissionType')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Receipt/Proof (Optional)</label>
            <input type="file" name="receipt_file" class="form-control" 
                   accept=".pdf,.jpg,.jpeg,.png" id="receiptFile">
            <small class="text-muted">Upload proof of commission (PDF, JPG, PNG) - Max 10MB</small>
            @error('receipt_file')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Applied Date</label>
            <input type="date" name="appliedDate" class="form-control" required
                   value="{{ old('appliedDate') }}">
            @error('appliedDate')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Amount (RM)</label>
            <input type="number" name="amount" class="form-control" required min="1"
                   value="{{ old('amount') }}">
            @error('amount')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Bank Account Number</label>
            <input type="text" name="accountNumber" class="form-control" required 
                   value="{{ old('accountNumber') }}" maxlength="20">
            <small class="text-muted">Enter your bank account number (max 20 characters)</small>
            @error('accountNumber')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Bank Name</label>
            <select name="bankName" class="form-control" required id="bankSelect">
                <option value="">-- Please Select Bank --</option>
                
                <optgroup label="Local Conventional Banks">
                    <option value="Maybank" {{ old('bankName') == 'Maybank' ? 'selected' : '' }}>Maybank</option>
                    <option value="CIMB Bank" {{ old('bankName') == 'CIMB Bank' ? 'selected' : '' }}>CIMB Bank</option>
                    <option value="Public Bank" {{ old('bankName') == 'Public Bank' ? 'selected' : '' }}>Public Bank</option>
                    <option value="RHB Bank" {{ old('bankName') == 'RHB Bank' ? 'selected' : '' }}>RHB Bank</option>
                    <option value="Hong Leong Bank" {{ old('bankName') == 'Hong Leong Bank' ? 'selected' : '' }}>Hong Leong Bank</option>
                    <option value="AmBank" {{ old('bankName') == 'AmBank' ? 'selected' : '' }}>AmBank</option>
                    <option value="Affin Bank" {{ old('bankName') == 'Affin Bank' ? 'selected' : '' }}>Affin Bank</option>
                    <option value="Alliance Bank" {{ old('bankName') == 'Alliance Bank' ? 'selected' : '' }}>Alliance Bank</option>
                </optgroup>
                
                <optgroup label="Islamic Banks">
                    <option value="Bank Islam" {{ old('bankName') == 'Bank Islam' ? 'selected' : '' }}>Bank Islam</option>
                    <option value="Bank Muamalat" {{ old('bankName') == 'Bank Muamalat' ? 'selected' : '' }}>Bank Muamalat</option>
                    <option value="Tabung Haji" {{ old('bankName') == 'Tabung Haji' ? 'selected' : '' }}>Tabung Haji</option>
                    <option value="Al Rajhi Bank" {{ old('bankName') == 'Al Rajhi Bank' ? 'selected' : '' }}>Al Rajhi Bank</option>
                    <option value="Kuwait Finance House" {{ old('bankName') == 'Kuwait Finance House' ? 'selected' : '' }}>Kuwait Finance House</option>
                </optgroup>
                
                <optgroup label="Government & Special Banks">
                    <option value="Bank Simpanan Nasional" {{ old('bankName') == 'Bank Simpanan Nasional' ? 'selected' : '' }}>Bank Simpanan Nasional (BSN)</option>
                    <option value="Agrobank" {{ old('bankName') == 'Agrobank' ? 'selected' : '' }}>Agrobank</option>
                    <option value="MBSB" {{ old('bankName') == 'MBSB' ? 'selected' : '' }}>MBSB Bank</option>
                    <option value="AEON Bank" {{ old('bankName') == 'AEON Bank' ? 'selected' : '' }}>AEON Bank</option>
                </optgroup>
                
                <optgroup label="Digital Banks & E-Wallets">
                    <option value="GX Bank" {{ old('bankName') == 'GX Bank' ? 'selected' : '' }}>GX Bank</option>
                    <option value="Boost Bank" {{ old('bankName') == 'Boost Bank' ? 'selected' : '' }}>Boost Bank</option>
                    <option value="KAF Digital Bank" {{ old('bankName') == 'KAF Digital Bank' ? 'selected' : '' }}>KAF Digital Bank</option>
                    <option value="Boost eWallet" {{ old('bankName') == 'Boost eWallet' ? 'selected' : '' }}>Boost eWallet</option>
                    <option value="Touch and Go" {{ old('bankName') == 'Touch and Go' ? 'selected' : '' }}>Touch and Go eWallet</option>
                    <option value="Big Pay" {{ old('bankName') == 'Big Pay' ? 'selected' : '' }}>Big Pay</option>
                </optgroup>
                
                <optgroup label="International Banks in Malaysia">
                    <option value="Standard Chartered Bank" {{ old('bankName') == 'Standard Chartered Bank' ? 'selected' : '' }}>Standard Chartered Bank</option>
                    <option value="HSBC" {{ old('bankName') == 'HSBC' ? 'selected' : '' }}>HSBC Bank</option>
                    <option value="Citibank" {{ old('bankName') == 'Citibank' ? 'selected' : '' }}>Citibank</option>
                    <option value="United Overseas Bank" {{ old('bankName') == 'United Overseas Bank' ? 'selected' : '' }}>United Overseas Bank (UOB)</option>
                    <option value="OCBC" {{ old('bankName') == 'OCBC' ? 'selected' : '' }}>OCBC Bank</option>
                    <option value="Bank of China" {{ old('bankName') == 'Bank of China' ? 'selected' : '' }}>Bank of China</option>
                    <option value="Bank of America" {{ old('bankName') == 'Bank of America' ? 'selected' : '' }}>Bank of America</option>
                </optgroup>
                
                <optgroup label="Other International Banks">
                    <option value="BNP Paribas" {{ old('bankName') == 'BNP Paribas' ? 'selected' : '' }}>BNP Paribas</option>
                    <option value="Bangkok Bank" {{ old('bankName') == 'Bangkok Bank' ? 'selected' : '' }}>Bangkok Bank</option>
                    <option value="China Construction Bank" {{ old('bankName') == 'China Construction Bank' ? 'selected' : '' }}>China Construction Bank</option>
                    <option value="Deutsche Bank" {{ old('bankName') == 'Deutsche Bank' ? 'selected' : '' }}>Deutsche Bank</option>
                    <option value="MUFG Bank" {{ old('bankName') == 'MUFG Bank' ? 'selected' : '' }}>MUFG Bank</option>
                    <option value="Mizuho Bank" {{ old('bankName') == 'Mizuho Bank' ? 'selected' : '' }}>Mizuho Bank</option>
                    <option value="ICBC" {{ old('bankName') == 'ICBC' ? 'selected' : '' }}>ICBC Bank</option>
                    <option value="JP Morgan Chase" {{ old('bankName') == 'JP Morgan Chase' ? 'selected' : '' }}>JP Morgan Chase</option>
                </optgroup>
                
                <optgroup label="Payment & Fintech">
                    <option value="FassPay" {{ old('bankName') == 'FassPay' ? 'selected' : '' }}>FassPay</option>
                    <option value="Finexus Cards" {{ old('bankName') == 'Finexus Cards' ? 'selected' : '' }}>Finexus Cards</option>
                    <option value="Merchantrade" {{ old('bankName') == 'Merchantrade' ? 'selected' : '' }}>Merchantrade</option>
                    <option value="RYT Bank" {{ old('bankName') == 'RYT Bank' ? 'selected' : '' }}>RYT Bank</option>
                </optgroup>
                
                <option value="Other" {{ old('bankName') == 'Other' ? 'selected' : '' }}>Other (Please specify)</option>
            </select>
            <small class="text-muted">Select your bank for commission payment</small>
            @error('bankName')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <!-- Field untuk Other Bank (akan muncul jika pilih "Other") -->
        <div class="mb-3" id="otherBankField" style="display: none;">
            <label class="form-label">Specify Other Bank</label>
            <input type="text" name="otherBankName" class="form-control" 
                   value="{{ old('otherBankName') }}" placeholder="Enter bank name" maxlength="100">
            @error('otherBankName')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <button type="button" id="submitBtn" class="btn btn-primary">Submit</button>
        <button type="button" id="cancelBtn" class="btn btn-secondary">Cancel</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const bankSelect = document.getElementById('bankSelect');
    const otherBankField = document.getElementById('otherBankField');
    const receiptFileInput = document.getElementById('receiptFile');
    
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
        const receiptFile = receiptFileInput ? receiptFileInput.files[0] : null;
        
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
        
        // Validasi file receipt jika ada yang diupload
        if (receiptFile) {
            const validTypes = ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png'];
            const maxSize = 10 * 1024 * 1024; // 10MB in bytes
            
            // Validasi jenis file
            if (!validTypes.includes(receiptFile.type)) {
                Swal.fire({ 
                    icon: 'error', 
                    title: 'Ralat', 
                    text: 'Fail receipt mestilah dalam format PDF, JPG, atau PNG!' 
                });
                return;
            }
            
            // Validasi saiz file
            if (receiptFile.size > maxSize) {
                Swal.fire({ 
                    icon: 'error', 
                    title: 'Error', 
                    text: 'File Size Cannot Exceed 10MB!' 
                });
                return;
            }
        }
        
        // Get final bank name for display
        let displayBankName = bankName;
        if (bankName === 'Other') {
            const otherBankName = document.querySelector('input[name="otherBankName"]');
            displayBankName = otherBankName ? otherBankName.value : 'Other Bank';
        }
        
        // Get receipt info for display
        let receiptInfo = '<p><strong>Receipt File:</strong> <em>Tiada fail diupload</em></p>';
        if (receiptFile) {
            const fileSizeMB = (receiptFile.size / (1024 * 1024)).toFixed(2);
            receiptInfo = `<p><strong>Receipt File:</strong> ${receiptFile.name} (${fileSizeMB} MB)</p>`;
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
                    ${receiptInfo}
                </div>
            `,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Simpan!',
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
        const hasChanges = document.querySelector('input[name="commissionType"]').value ||
                          document.querySelector('input[name="appliedDate"]').value ||
                          document.querySelector('input[name="amount"]').value ||
                          document.querySelector('input[name="accountNumber"]').value ||
                          (bankSelect && bankSelect.value) ||
                          document.querySelector('input[name="otherBankName"]').value ||
                          (receiptFileInput && receiptFileInput.files.length > 0);
        
        if (hasChanges) {
            Swal.fire({
                title: 'Adakah anda pasti?',
                text: "Semua maklumat yang belum disimpan akan hilang.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Batal',
                cancelButtonText: 'Kembali',
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