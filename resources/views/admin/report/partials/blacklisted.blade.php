@extends('layouts.it_admin')
@section('title', 'Blacklist Report')

@section('content')

<!-- Tambahkan di bagian atas file blacklisted.blade.php, setelah @section('content') -->
@php
    \Log::info('=== BLADE DEBUG START ===');
    \Log::info('Data count: ' . count($data));
    if(count($data) > 0) {
        $first = $data->first();
        \Log::info('First record in blade:', [
            'userID' => $first->userID ?? 'NULL',
            'name' => $first->name ?? 'NULL',
            'email' => $first->email ?? 'NULL',
            'noHP' => $first->noHP ?? 'NULL',
            'noIC' => $first->noIC ?? 'NULL',
            'customerType' => $first->customerType ?? 'NULL'
        ]);
        
        // Debug semua field
        \Log::info('All fields available:', array_keys((array)$first));
    }
@endphp

<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">
                <i class="fas fa-ban text-danger me-2"></i>Blacklist Report
            </h4>
            <p class="text-muted mb-0">Comprehensive report of blacklisted customers</p>
        </div>
        
        <!-- Export Buttons -->
        <div class="btn-group">
            <button type="button" class="btn btn-danger dropdown-toggle" data-bs-toggle="dropdown">
                <i class="fas fa-download me-2"></i>Export Report
            </button>
            <ul class="dropdown-menu">
                <li>
                    <a class="dropdown-item" href="{{ route('reports.blacklisted.exportPdf') }}">
                        <i class="fas fa-file-pdf text-danger me-2"></i>Export as PDF
                    </a>
                </li>
                <li>
                    <a class="dropdown-item" href="{{ route('reports.blacklisted.exportExcel') }}">
                        <i class="fas fa-file-excel text-success me-2"></i>Export as Excel
                    </a>
                </li>
            </ul>
            <a href="{{ route('reports.index') }}" class="btn btn-outline-danger ms-2">
                <i class="fas fa-arrow-left me-1"></i>Back to Reports
            </a>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="section-card mb-4">
        <h6 class="mb-3">
            <i class="fas fa-filter me-2"></i>Filter Options
        </h6>
        <div id="filterForm">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Search</label>
                    <input type="text" id="searchInput" name="search" class="form-control" 
                        placeholder="Search name, email, ID, IC, or phone..." 
                        value="">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Reason Contains</label>
                    <input type="text" id="reasonInput" name="reason" class="form-control" 
                        placeholder="e.g., payment, damage, late..."
                        value="">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="button" id="applyFilterBtn" class="btn btn-danger w-100">
                        <i class="fas fa-search me-1"></i>Apply Filter
                    </button>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="button" id="resetFilterBtn" class="btn btn-outline-secondary w-100">
                        <i class="fas fa-redo me-1"></i>Reset
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading Spinner -->
    <div id="loadingSpinner" class="text-center py-4 d-none">
        <div class="spinner-border text-danger" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <p class="mt-2 text-muted">Loading blacklist data...</p>
    </div>

    <!-- Summary Statistics -->
    <div id="summarySection">
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="metric-card bg-dark bg-opacity-10">
                    <div class="metric-title">Total Blacklisted</div>
                    <div class="metric-value" id="totalCount">{{ number_format($summary['total'] ?? 0) }}</div>
                    <div class="metric-subtitle">All Records</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="metric-card bg-primary bg-opacity-10">
                    <div class="metric-title">Students</div>
                    <div class="metric-value text-primary" id="studentCount">{{ number_format($summary['students'] ?? 0) }}</div>
                    <div class="metric-subtitle" id="studentPercent">
                        {{ $summary['total'] > 0 ? round(($summary['students'] / $summary['total']) * 100, 1) : 0 }}% of total
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="metric-card bg-success bg-opacity-10">
                    <div class="metric-title">Staff</div>
                    <div class="metric-value text-success" id="staffCount">{{ number_format($summary['staff'] ?? 0) }}</div>
                    <div class="metric-subtitle" id="staffPercent">
                        {{ $summary['total'] > 0 ? round(($summary['staff'] / $summary['total']) * 100, 1) : 0 }}% of total
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Blacklist Data Table -->
    <div class="section-card">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="mb-0">
                <i class="fas fa-list me-2"></i>Blacklist Records (<span id="recordCount">{{ count($data) }}</span>)
            </h6>
            <div>
                <span class="badge bg-danger" id="badgeTotal">
                    Total: {{ $summary['total'] ?? 0 }}
                </span>
                <span class="badge bg-primary ms-1" id="badgeStudents">
                    Students: {{ $summary['students'] ?? 0 }}
                </span>
                <span class="badge bg-success ms-1" id="badgeStaff">
                    Staff: {{ $summary['staff'] ?? 0 }}
                </span>
            </div>
        </div>
        
        <div id="tableContainer">
            @if(count($data) > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>User ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>IC Number</th>
                                <th>Customer Type</th>
                                <th>Reason</th>
                                <th>Admin</th>
                            </tr>
                        </thead>
                        <tbody id="blacklistTableBody">
                            @foreach($data as $index => $record)
                            <tr>
                                <td>
                                    <span class="badge bg-dark">{{ $record->userID }}</span>
                                    <br>
                                    <small class="text-muted">Debug: {{ json_encode($record) }}</small>
                                </td>
                                <td>
                                    <div class="fw-bold">{{ $record->name }}</div>
                                </td>
                                <td>
                                    <small>{{ $record->email }}</small>
                                </td>
                                <td>
                                    @php
                                        // Debug untuk phone
                                        $phoneValue = $record->noHP ?? 'NULL in blade';
                                        \Log::info("Record {$index} - userID: {$record->userID}, phone: {$phoneValue}, IC: " . ($record->noIC ?? 'NULL in blade'));
                                    @endphp
                                    
                                    @if($record->noHP && $record->noHP !== 'NULL' && trim($record->noHP) !== '')
                                        {{ $record->noHP }}
                                    @else
                                        <span class="text-muted">N/A</span>
                                        <br><small class="text-danger">Debug: {{ $record->noHP ?? 'empty' }}</small>
                                    @endif
                                </td>
                                <td>
                                    @if($record->noIC && $record->noIC !== 'NULL' && trim($record->noIC) !== '')
                                        {{ $record->noIC }}
                                    @else
                                        <span class="text-muted">N/A</span>
                                        <br><small class="text-danger">Debug: {{ $record->noIC ?? 'empty' }}</small>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-{{ $record->customerType == 'student' ? 'primary' : 'success' }}">
                                        {{ ucfirst($record->customerType) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="reason-box" style="max-width: 250px;">
                                        <small>{{ $record->reason ?: 'Not specified' }}</small>
                                    </div>
                                </td>
                                <td>
                                    @if($record->admin_name)
                                        <div>
                                            <div class="fw-bold">{{ $record->admin_name }}</div>
                                            <small class="text-muted">ID: {{ $record->adminID }}</small>
                                        </div>
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-ban fa-3x text-muted mb-3"></i>
                    <h5>No Blacklist Records Found</h5>
                    <p class="text-muted">No blacklist records found in the system</p>
                </div>
            @endif
        </div>
    </div>
</div>

@push('styles')
<style>
    .reason-box {
        max-height: 60px;
        overflow-y: auto;
        padding: 5px;
        background: #f8f9fa;
        border-radius: 4px;
        border-left: 3px solid #dc3545;
    }
    
    .metric-card {
        background: white;
        border-radius: 10px;
        padding: 15px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        transition: transform 0.2s;
        min-height: 110px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    
    .metric-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }
    
    .section-card {
        background: white;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.08);
        border: 1px solid #e3e3e3;
        margin-bottom: 20px;
    }
    
    .table th {
        font-weight: 600;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .table td {
        vertical-align: middle;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const applyBtn = document.getElementById('applyFilterBtn');
    const resetBtn = document.getElementById('resetFilterBtn');
    const searchInput = document.getElementById('searchInput');
    const reasonInput = document.getElementById('reasonInput');
    const loadingSpinner = document.getElementById('loadingSpinner');
    const tableBody = document.getElementById('blacklistTableBody');
    
    // Function untuk apply filter
    function applyFilter() {
        const search = searchInput.value;
        const reason = reasonInput.value;
        
        // Show loading
        loadingSpinner.classList.remove('d-none');
        
        // Send AJAX request
        fetch("{{ route('reports.blacklisted.filter') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                search: search,
                reason: reason
            })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            console.log('AJAX Response Data:', data); // Debug
            updateBlacklistTable(data);
            loadingSpinner.classList.add('d-none');
        })
        .catch(error => {
            console.error('Error:', error);
            loadingSpinner.classList.add('d-none');
            alert('Failed to apply filter: ' + error.message);
        });
    }
    
    // Function untuk reset filter
    function resetFilter() {
        searchInput.value = '';
        reasonInput.value = '';
        applyFilter();
    }
    
    // Function untuk update table
    function updateBlacklistTable(data) {
        console.log('Updating table with data:', data); // Debug
        
        // Update table rows
        if (data.data && data.data.length > 0) {
            let html = '';
            data.data.forEach((record, index) => {
                html += `
                <tr>
                    <td>
                        <span class="badge bg-dark">${record.userID}</span>
                        <br>
                        <small class="text-muted">Debug: ${JSON.stringify(record)}</small>
                    </td>
                    <td><div class="fw-bold">${record.name}</div></td>
                    <td><small>${record.email}</small></td>
                    <td>
                        ${record.noHP && record.noHP !== 'NULL' && record.noHP.trim() !== '' ? 
                            record.noHP : 
                            '<span class="text-muted">N/A</span><br><small class="text-danger">Debug: ' + (record.noHP || 'empty') + '</small>'
                        }
                    </td>
                    <td>
                        ${record.noIC && record.noIC !== 'NULL' && record.noIC.trim() !== '' ? 
                            record.noIC : 
                            '<span class="text-muted">N/A</span><br><small class="text-danger">Debug: ' + (record.noIC || 'empty') + '</small>'
                        }
                    </td>
                    <td>
                        <span class="badge bg-${record.customerType == 'student' ? 'primary' : 'success'}">
                            ${record.customerType ? record.customerType.charAt(0).toUpperCase() + record.customerType.slice(1) : 'Unknown'}
                        </span>
                    </td>
                    <td>
                        <div class="reason-box" style="max-width: 250px;">
                            <small>${record.reason || 'Not specified'}</small>
                        </div>
                    </td>
                    <td>
                        ${record.admin_name ? 
                            `<div><div class="fw-bold">${record.admin_name}</div><small class="text-muted">ID: ${record.adminID}</small></div>` : 
                            '<span class="text-muted">N/A</span>'
                        }
                    </td>
                </tr>`;
            });
            tableBody.innerHTML = html;
            
            // Show table
            document.querySelector('.table-responsive').classList.remove('d-none');
            const noDataDiv = document.querySelector('#tableContainer .text-center');
            if (noDataDiv) {
                noDataDiv.classList.add('d-none');
            }
        } else {
            // Hide table and show no data message
            document.querySelector('.table-responsive').classList.add('d-none');
            let noDataDiv = document.querySelector('#tableContainer .text-center');
            if (!noDataDiv) {
                noDataDiv = document.createElement('div');
                noDataDiv.className = 'text-center py-5';
                noDataDiv.innerHTML = `
                    <i class="fas fa-ban fa-3x text-muted mb-3"></i>
                    <h5>No Blacklist Records Found</h5>
                    <p class="text-muted">No records match your filter criteria</p>
                `;
                document.getElementById('tableContainer').appendChild(noDataDiv);
            } else {
                noDataDiv.classList.remove('d-none');
            }
        }
        
        // Update summary statistics
        if (data.summary) {
            document.getElementById('totalCount').textContent = data.summary.total.toLocaleString();
            document.getElementById('studentCount').textContent = data.summary.students.toLocaleString();
            document.getElementById('staffCount').textContent = data.summary.staff.toLocaleString();
            
            // Update percentages
            const total = data.summary.total;
            const studentPercent = total > 0 ? Math.round((data.summary.students / total) * 1000) / 10 : 0;
            const staffPercent = total > 0 ? Math.round((data.summary.staff / total) * 1000) / 10 : 0;
            
            document.getElementById('studentPercent').textContent = studentPercent + '% of total';
            document.getElementById('staffPercent').textContent = staffPercent + '% of total';
            
            // Update badges
            document.getElementById('badgeTotal').textContent = 'Total: ' + data.summary.total;
            document.getElementById('badgeStudents').textContent = 'Students: ' + data.summary.students;
            document.getElementById('badgeStaff').textContent = 'Staff: ' + data.summary.staff;
        }
        
        // Update record count
        document.getElementById('recordCount').textContent = data.data ? data.data.length : 0;
    }
    
    // Event listeners
    applyBtn.addEventListener('click', applyFilter);
    resetBtn.addEventListener('click', resetFilter);
    
    // Auto-apply filter when Enter is pressed in input fields
    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            applyFilter();
        }
    });
    
    reasonInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            applyFilter();
        }
    });
});
</script>
@endpush

@endsection