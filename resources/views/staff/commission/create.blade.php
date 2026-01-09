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

    <form id="commissionForm" action="{{ route('commission.store') }}" method="POST">
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

        <button type="button" id="submitBtn" class="btn btn-primary">Simpan</button>
        <button type="button" id="cancelBtn" class="btn btn-secondary">Batal</button>
    </form>
</div>

<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Button Simpan
    document.getElementById('submitBtn').addEventListener('click', function(e) {
        e.preventDefault();
        
        // Validasi form
        const commissionType = document.querySelector('input[name="commissionType"]').value;
        const appliedDate = document.querySelector('input[name="appliedDate"]').value;
        const amount = document.querySelector('input[name="amount"]').value;
        
        // Check jika ada field kosong
        if (!commissionType.trim()) {
            Swal.fire({
                icon: 'error',
                title: 'Ralat',
                text: 'Sila isi Commission Type!',
            });
            return;
        }
        
        if (!appliedDate) {
            Swal.fire({
                icon: 'error',
                title: 'Ralat',
                text: 'Sila pilih Applied Date!',
            });
            return;
        }
        
        if (!amount || amount < 1) {
            Swal.fire({
                icon: 'error',
                title: 'Ralat',
                text: 'Sila isi Amount dengan nilai 1 atau lebih!',
            });
            return;
        }
        
        // Show confirmation popup
        Swal.fire({
            title: 'Adakah anda pasti?',
            html: `
                <div class="text-start">
                    <p><strong>Commission Type:</strong> ${commissionType}</p>
                    <p><strong>Applied Date:</strong> ${appliedDate}</p>
                    <p><strong>Amount:</strong> RM ${amount}</p>
                </div>
            `,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Simpan!',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Submit form jika user klik Ya
                document.getElementById('commissionForm').submit();
            }
        });
    });
    
    // Button Batal
    document.getElementById('cancelBtn').addEventListener('click', function(e) {
        e.preventDefault();
        
        // Check jika ada perubahan dalam form
        const commissionType = document.querySelector('input[name="commissionType"]').value;
        const appliedDate = document.querySelector('input[name="appliedDate"]').value;
        const amount = document.querySelector('input[name="amount"]').value;
        
        const hasChanges = commissionType || appliedDate || amount;
        
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
                    // Redirect ke halaman index
                    window.location.href = "{{ route('commission.index') }}";
                }
            });
        } else {
            // Jika tiada perubahan, terus redirect
            window.location.href = "{{ route('commission.index') }}";
        }
    });
});
</script>
@endsection