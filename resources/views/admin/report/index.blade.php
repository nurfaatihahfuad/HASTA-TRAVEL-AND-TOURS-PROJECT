@extends('layouts.it_admin')

@section('title', 'Reports - HASTA')

@section('content')
<div class="section-card">
    <h3><i class="fas fa-chart-bar me-2"></i> Reports</h3>

    <!-- Category Dropdown -->
    <div class="mb-3">
        <label class="form-label fw-bold">Choose Report Category</label>
        <select class="form-select w-auto" id="reportCategory">
            <option value="" disabled selected>Choose Category</option>
            <option value="revenue">Revenue Report</option>
            <option value="total_booking">Total Booking</option>
            <option value="top_college">Top College Booking Report</option>
            <option value="blacklisted">Blacklisted Customer</option>
        </select>
    </div>

    <!-- Loading Spinner -->
    <div id="loadingSpinner" class="text-center py-5" style="display: none;">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <p class="mt-2">Loading report data...</p>
    </div>

    <!-- Report Content -->
    <div id="report-content">
        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i> Please select a category to view report.
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Report page loaded');
    
    const reportCategory = document.getElementById('reportCategory');
    const reportContent = document.getElementById('report-content');
    const loadingSpinner = document.getElementById('loadingSpinner');
    
    reportCategory.addEventListener('change', function() {
        const category = this.value;
        if (!category) return;
        
        console.log('Selected category:', category);
        
        // Show loading
        loadingSpinner.style.display = 'block';
        reportContent.innerHTML = '';
        
        // Fetch report data via AJAX
        fetch(`/admin/reports/${category}/ajax`)
            .then(response => {
                console.log('Response status:', response.status);
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Received data:', data);
                loadingSpinner.style.display = 'none';
                
                // Render based on category
                if (category === 'revenue') {
                    renderRevenueReport(data);
                } else if (category === 'total_booking') {
                    renderTotalBookingReport(data);
                } else if (category === 'top_college') {
                    renderTopCollegeReport(data);
                } else if (category === 'blacklisted') {
                    renderBlacklistedReport(data);
                }
            })
            .catch(error => {
                console.error('Error loading report:', error);
                loadingSpinner.style.display = 'none';
                reportContent.innerHTML = `
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Error loading report: ${error.message}
                    </div>
                `;
            });
    });
    
    function renderRevenueReport(data) {
        let html = `
            <h5 class="mb-3">Revenue Report</h5>
            
            <!-- Summary Cards -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <h6>Total Sales</h6>
                            <p class="fs-5 mb-0 text-primary">RM ${parseFloat(data.summary.total_sales || 0).toFixed(2)}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <h6>Total Income</h6>
                            <p class="fs-5 mb-0 text-success">RM ${parseFloat(data.summary.total_income || 0).toFixed(2)}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <h6>Average Duration</h6>
                            <p class="fs-5 mb-0 text-warning">${parseFloat(data.summary.avg_duration || 0).toFixed(1)} hrs</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <h6>Completed Payments</h6>
                            <p class="fs-5 mb-0 text-info">${data.summary.completed_payments || 0}</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Revenue Table -->
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Vehicle</th>
                            <th>Booking ID</th>
                            <th>Duration (hrs)</th>
                            <th>Payment Type</th>
                            <th>Total Amount</th>
                        </tr>
                    </thead>
                    <tbody>`;
        
        if (data.data && data.data.length > 0) {
            data.data.forEach(row => {
                html += `
                    <tr>
                        <td>${row.vehicleName || '-'}</td>
                        <td>${row.bookingID || '-'}</td>
                        <td>${row.duration || '0'}</td>
                        <td>${row.paymentType || '-'}</td>
                        <td>RM ${parseFloat(row.totalAmount || 0).toFixed(2)}</td>
                    </tr>`;
            });
        } else {
            html += `
                <tr>
                    <td colspan="5" class="text-center py-4">
                        <div class="text-muted">
                            <i class="fas fa-info-circle me-2"></i>
                            No revenue data available. There might be no approved payments yet.
                        </div>
                    </td>
                </tr>`;
        }
        
        html += `
                    </tbody>
                </table>
            </div>
            
            <!-- Export Buttons -->
            <div class="mt-3">
                <a href="/admin/reports/revenue/export-pdf" class="btn btn-danger">
                    <i class="fas fa-file-pdf"></i> Export PDF
                </a>
                <a href="/admin/reports/revenue/export-excel" class="btn btn-success">
                    <i class="fas fa-file-excel"></i> Export Excel
                </a>
            </div>`;
        
        reportContent.innerHTML = html;
    }
    
    function renderTotalBookingReport(data) {
        let html = `
            <h5 class="mb-3">Total Booking Report</h5>
            
            <!-- Summary Cards -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <h6>Total Bookings</h6>
                            <p class="fs-5 mb-0 text-primary">${data.summary.total || 0}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <h6>Successful</h6>
                            <p class="fs-5 mb-0 text-success">${data.summary.successful || 0}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <h6>Pending</h6>
                            <p class="fs-5 mb-0 text-warning">${data.summary.pending || 0}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <h6>Rejected</h6>
                            <p class="fs-5 mb-0 text-danger">${data.summary.rejected || 0}</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Booking ID</th>
                            <th>User ID</th>
                            <th>Name</th>
                            <th>Vehicle</th>
                            <th>Pickup Date/Time</th>
                            <th>Return Date/Time</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>`;
        
        if (data.data && data.data.length > 0) {
            data.data.forEach(row => {
                html += `
                    <tr>
                        <td>${row.bookingID || '-'}</td>
                        <td>${row.userID || '-'}</td>
                        <td>${row.name || '-'}</td>
                        <td>${row.vehicleName || '-'}</td>
                        <td>${formatDateTime(row.pickup_dateTime)}</td>
                        <td>${formatDateTime(row.return_dateTime)}</td>
                        <td><span class="badge ${getStatusBadgeClass(row.bookingStatus)}">${row.bookingStatus || '-'}</span></td>
                    </tr>`;
            });
        } else {
            html += `
                <tr>
                    <td colspan="7" class="text-center py-4">
                        <div class="text-muted">
                            <i class="fas fa-info-circle me-2"></i>
                            No booking data available.
                        </div>
                    </td>
                </tr>`;
        }
        
        html += `
                    </tbody>
                </table>
            </div>
            
            <!-- Export Buttons -->
            <div class="mt-3">
                <a href="/admin/reports/total_booking/export-pdf" class="btn btn-danger">
                    <i class="fas fa-file-pdf"></i> Export PDF
                </a>
                <a href="/admin/reports/total_booking/export-excel" class="btn btn-success">
                    <i class="fas fa-file-excel"></i> Export Excel
                </a>
            </div>`;
        
        reportContent.innerHTML = html;
    }
    
    function renderTopCollegeReport(data) {
    console.log('Rendering top college report:', data);
    
    let html = `
        <h5 class="mb-3"><i class="fas fa-university me-2"></i>Top College Booking Report</h5>
        
        <!-- Filter Form -->
        <form id="filterForm" class="row g-3 mb-4">
            <div class="col-md-3">
                <select name="month" class="form-select">
                    <option value="">All Months</option>`;
    
    // Generate month options
    const months = ['January', 'February', 'March', 'April', 'May', 'June', 
                    'July', 'August', 'September', 'October', 'November', 'December'];
    months.forEach((month, index) => {
        html += `<option value="${index + 1}">${month}</option>`;
    });
    
    html += `
                </select>
            </div>
            <div class="col-md-3">
                <select name="year" class="form-select">
                    <option value="">All Years</option>`;
    
    // Generate year options
    const currentYear = new Date().getFullYear();
    for (let year = currentYear; year >= currentYear - 5; year--) {
        html += `<option value="${year}">${year}</option>`;
    }
    
    html += `
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-filter me-2"></i> Filter
                </button>
            </div>
        </form>

        <!-- Export Buttons -->
        <div class="mb-4">
            <a href="/admin/reports/top_college/export-pdf" class="btn btn-danger me-2">
                <i class="fas fa-file-pdf me-1"></i> Export PDF
            </a>
            <a href="/admin/reports/top_college/export-excel" class="btn btn-success">
                <i class="fas fa-file-excel me-1"></i> Export Excel
            </a>
        </div>
        
        <!-- Table -->
        <div class="table-responsive">
            <table class="table table-hover" id="topCollegeTable">
                <thead class="table-light">
                    <tr>
                        <th>Booking ID</th>
                        <th>User ID</th>
                        <th>Name</th>
                        <th>College</th>
                        <th>Vehicle</th>
                        <th>Pickup Date</th>
                        <th>Return Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody id="topCollegeTableBody">`;
    
    if (data.data && data.data.length > 0) {
        data.data.forEach(row => {
            // Format dates
            const pickupDate = formatDate(row.pickup_dateTime);
            const returnDate = formatDate(row.return_dateTime);
            
            // Status badge class
            const statusClass = getTopCollegeStatusClass(row.bookingStatus);
            
            html += `
                <tr>
                    <td><code>${row.bookingID || 'N/A'}</code></td>
                    <td>${row.userID || 'N/A'}</td>
                    <td>${row.name || 'N/A'}</td>
                    <td>${row.collegeName || 'N/A'}</td>
                    <td>${row.vehicleName || 'N/A'}</td>
                    <td>${pickupDate}</td>
                    <td>${returnDate}</td>
                    <td>
                        <span class="badge bg-${statusClass}">
                            ${row.bookingStatus ? row.bookingStatus.charAt(0).toUpperCase() + row.bookingStatus.slice(1) : 'Unknown'}
                        </span>
                    </td>
                </tr>`;
        });
    } else {
        html += `
            <tr>
                <td colspan="8" class="text-center py-5">
                    <div class="text-muted">
                        <i class="fas fa-university fa-3x mb-3"></i>
                        <h5 class="mb-2">No College Booking Data Found</h5>
                        <p class="mb-0">
                            There are no bookings from students with college information.
                        </p>
                    </div>
                </td>
            </tr>`;
    }
    
    html += `
                </tbody>
            </table>
        </div>`;
    
    reportContent.innerHTML = html;
    
    // ============================================
    // FIXED FILTER FORM EVENT LISTENER (POST method)
    // ============================================
    const filterForm = document.getElementById('filterForm');
    if (filterForm) {
        filterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const month = filterForm.querySelector('[name="month"]').value;
            const year = filterForm.querySelector('[name="year"]').value;
            
            // Show loading
            const tbody = document.getElementById('topCollegeTableBody');
            tbody.innerHTML = `
                <tr>
                    <td colspan="8" class="text-center py-4">
                        <div class="spinner-border spinner-border-sm text-primary me-2" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        Loading filtered data...
                    </td>
                </tr>`;
            
            // Use POST method with JSON body
            fetch(`/admin/reports/top_college/filter`, {
                method: 'POST',
                headers: { 
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    month: month || null,
                    year: year || null
                })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                return response.json();
            })
            .then(filteredData => {
                if (!filteredData || filteredData.length === 0) {
                    tbody.innerHTML = `
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="fas fa-info-circle fa-2x mb-2"></i>
                                    <p class="mb-0">No bookings found for the selected filter</p>
                                </div>
                            </td>
                        </tr>`;
                } else {
                    tbody.innerHTML = '';
                    filteredData.forEach(row => {
                        const pickupDate = formatDate(row.pickup_dateTime);
                        const returnDate = formatDate(row.return_dateTime);
                        const statusClass = getTopCollegeStatusClass(row.bookingStatus);
                        
                        tbody.innerHTML += `
                            <tr>
                                <td><code>${row.bookingID || 'N/A'}</code></td>
                                <td>${row.userID || 'N/A'}</td>
                                <td>${row.name || 'N/A'}</td>
                                <td>${row.collegeName || 'N/A'}</td>
                                <td>${row.vehicleName || 'N/A'}</td>
                                <td>${pickupDate}</td>
                                <td>${returnDate}</td>
                                <td>
                                    <span class="badge bg-${statusClass}">
                                        ${row.bookingStatus ? row.bookingStatus.charAt(0).toUpperCase() + row.bookingStatus.slice(1) : 'Unknown'}
                                    </span>
                                </td>
                            </tr>`;
                    });
                }
            })
            .catch(error => {
                console.error('Filter error:', error);
                tbody.innerHTML = `
                    <tr>
                        <td colspan="8" class="text-center py-4 text-danger">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Error loading filtered data: ${error.message}
                        </td>
                    </tr>`;
            });
        });
    }
    // ============================================
}
    
    function renderBlacklistedReport(data) {
        let html = `
            <h5 class="mb-3"><i class="fa fa-user-times me-2"></i>Blacklisted Customers</h5>
            
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>User ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Blacklisted Since</th>
                            <th>Reason</th>
                        </tr>
                    </thead>
                    <tbody>`;
        
        if (data.data && data.data.length > 0) {
            data.data.forEach(row => {
                html += `
                    <tr>
                        <td><code>${row.userID || 'N/A'}</code></td>
                        <td>${row.name || 'N/A'}</td>
                        <td>${row.email || 'N/A'}</td>
                        <td>${row.phone || 'N/A'}</td>
                        <td>${formatDate(row.blacklisted_at || row.created_at)}</td>
                        <td>${row.blacklist_reason || 'Not specified'}</td>
                    </tr>`;
            });
        } else {
            html += `
                <tr>
                    <td colspan="6" class="text-center py-5">
                        <div class="text-muted">
                            <i class="fa fa-user-times fa-3x mb-3"></i>
                            <h5 class="mb-2">No Blacklisted Customers</h5>
                            <p class="mb-0">No customers are currently blacklisted.</p>
                        </div>
                    </td>
                </tr>`;
        }
        
        html += `
                    </tbody>
                </table>
            </div>
            
            <!-- Export Buttons -->
            <div class="mt-3">
                <a href="/admin/reports/blacklisted/export-pdf" class="btn btn-danger">
                    <i class="fas fa-file-pdf"></i> Export PDF
                </a>
                <a href="/admin/reports/blacklisted/export-excel" class="btn btn-success">
                    <i class="fas fa-file-excel"></i> Export Excel
                </a>
            </div>`;
        
        reportContent.innerHTML = html;
    }
    
    function formatDateTime(dateTime) {
        if (!dateTime) return '-';
        const date = new Date(dateTime);
        return date.toLocaleString('en-MY');
    }
    
    function formatDate(dateTime) {
        if (!dateTime) return 'N/A';
        const date = new Date(dateTime);
        return date.toLocaleDateString('en-MY', {
            day: '2-digit',
            month: 'short',
            year: 'numeric'
        });
    }
    
    function getStatusBadgeClass(status) {
        if (!status) return 'bg-secondary';
        switch(status.toLowerCase()) {
            case 'successful': return 'bg-success';
            case 'pending': return 'bg-warning text-dark';
            case 'rejected': return 'bg-danger';
            default: return 'bg-secondary';
        }
    }
    
    function getTopCollegeStatusClass(status) {
        if (!status) return 'secondary';
        switch(status.toLowerCase()) {
            case 'successful': return 'success';
            case 'pending': return 'warning';
            case 'rejected': return 'danger';
            default: return 'secondary';
        }
    }
});
</script>
@endpush