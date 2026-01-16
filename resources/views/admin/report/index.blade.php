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

// =============== UTILITY FUNCTIONS ===============
// Function to format date-time
function formatDateTime(dateTimeString) {
    if (!dateTimeString) return 'N/A';
    
    try {
        const date = new Date(dateTimeString);
        return date.toLocaleString('en-MY', {
            year: 'numeric',
            month: 'short',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    } catch (e) {
        console.error('Error formatting date:', e);
        return dateTimeString;
    }
}

// Function to format date only (for top college report)
function formatDate(dateString) {
    if (!dateString) return 'N/A';
    
    try {
        const date = new Date(dateString);
        return date.toLocaleDateString('en-MY', {
            year: 'numeric',
            month: 'short',
            day: 'numeric'
        });
    } catch (e) {
        console.error('Error formatting date:', e);
        return dateString;
    }
}

// Function to get status badge class for booking report
function getStatusBadgeClass(status) {
    if (!status) return 'bg-secondary';
    
    const statusLower = status.toLowerCase();
    if (statusLower === 'successful' || statusLower === 'approved') {
        return 'bg-success';
    } else if (statusLower === 'pending') {
        return 'bg-warning text-dark';
    } else if (statusLower === 'rejected' || statusLower === 'cancelled') {
        return 'bg-danger';
    } else {
        return 'bg-secondary';
    }
}

// Function to get status class for top college report
function getTopCollegeStatusClass(status) {
    if (!status) return 'secondary';
    
    const statusLower = status.toLowerCase();
    if (statusLower === 'successful' || statusLower === 'approved') {
        return 'success';
    } else if (statusLower === 'pending') {
        return 'warning';
    } else if (statusLower === 'rejected' || statusLower === 'cancelled') {
        return 'danger';
    } else {
        return 'secondary';
    }
}
// =============== END UTILITY FUNCTIONS ===============

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
        
        <!-- Filter Form -->
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <select id="filterMonth" class="form-select">
                    <option value="">All Months</option>
                    <option value="1">January</option>
                    <option value="2">February</option>
                    <option value="3">March</option>
                    <option value="4">April</option>
                    <option value="5">May</option>
                    <option value="6">June</option>
                    <option value="7">July</option>
                    <option value="8">August</option>
                    <option value="9">September</option>
                    <option value="10">October</option>
                    <option value="11">November</option>
                    <option value="12">December</option>
                </select>
            </div>
            <div class="col-md-3">
                <select id="filterYear" class="form-select">
                    <option value="">All Years</option>`;
    
    // Generate year options
    const currentYear = new Date().getFullYear();
    for (let year = currentYear; year >= currentYear - 5; year--) {
        html += `<option value="${year}">${year}</option>`;
    }
    
    html += `
                </select>
            </div>
            <div class="col-md-2">
                <button id="applyFilter" class="btn btn-primary w-100">
                    <i class="fas fa-filter me-2"></i> Filter
                </button>
            </div>
            <div class="col-md-2">
                <button id="resetFilter" class="btn btn-outline-secondary w-100">
                    <i class="fas fa-redo me-1"></i> Reset
                </button>
            </div>
            
        </div>
        
        <!-- Export Buttons -->
        <div class="mb-4 d-flex justify-content-between align-items-center">
            <div>
                <a href="/admin/reports/revenue/export-pdf" class="btn btn-danger me-2" id="pdfExportBtn">
                    <i class="fas fa-file-pdf me-1"></i> Export PDF
                </a>
                <a href="/admin/reports/revenue/export-excel" class="btn btn-success" id="excelExportBtn">
                    <i class="fas fa-file-excel me-1"></i> Export Excel
                </a>
            </div>
            <div class="text-muted">
                <small><i class="fas fa-info-circle me-1"></i> Export includes current filter</small>
            </div>
        </div>
        
        <!-- Loading for Filter -->
        <div id="filterLoading" class="text-center py-3" style="display: none;">
            <div class="spinner-border spinner-border-sm text-primary me-2" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <span class="text-muted">Loading filtered data...</span>
        </div>
        
        <!-- Summary Cards - TANPA TOTAL INCOME -->
        <div class="row mb-4" id="summaryCards">
            <div class="col-md-4"> <!-- Changed from col-md-3 to col-md-4 -->
                <div class="card text-center">
                    <div class="card-body">
                        <h6>Total Sales</h6>
                        <p class="fs-5 mb-0 text-primary" id="totalSales">RM ${parseFloat(data.summary.total_sales || 0).toFixed(2)}</p>
                        <small class="text-muted">All payments</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4"> <!-- Changed from col-md-3 to col-md-4 -->
                <div class="card text-center">
                    <div class="card-body">
                        <h6>Average Duration</h6>
                        <p class="fs-5 mb-0 text-warning" id="avgDuration">${parseFloat(data.summary.avg_duration || 0).toFixed(1)} hrs</p>
                        <small class="text-muted">Per booking</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4"> <!-- Changed from col-md-3 to col-md-4 -->
                <div class="card text-center">
                    <div class="card-body">
                        <h6>Approved Payments</h6>
                        <p class="fs-5 mb-0 text-info" id="completedPayments">${data.summary.completed_payments || 0}</p>
                        <small class="text-muted">Successful bookings</small>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Chart Section -->
        <div class="card mb-4" id="chartSection" style="${!data.chart || data.chart.labels.length === 0 ? 'display: none;' : ''}">
            <div class="card-header">
                <h6 class="mb-0">Revenue by Vehicle</h6>
            </div>
            <div class="card-body">
                <canvas id="revenueChart" height="100"></canvas>
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
                <tbody id="revenueTableBody">`;
    
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
        </div>`;
    
    reportContent.innerHTML = html;
    
    // Initialize chart if data exists
    if (data.chart && data.chart.labels && data.chart.labels.length > 0) {
        initializeRevenueChart(data.chart);
    }
        
        // Add event listeners for filter buttons
        const applyBtn = document.getElementById('applyFilter');
        const resetBtn = document.getElementById('resetFilter');
        const refreshBtn = document.getElementById('refreshData');
        const monthSelect = document.getElementById('filterMonth');
        const yearSelect = document.getElementById('filterYear');
        const pdfExportBtn = document.getElementById('pdfExportBtn');
        const excelExportBtn = document.getElementById('excelExportBtn');
        
        let currentFilter = {
            month: '',
            year: ''
        };
        
        // Function to update export links with current filter
        function updateExportLinks() {
            const month = currentFilter.month ? `month=${currentFilter.month}` : '';
            const year = currentFilter.year ? `year=${currentFilter.year}` : '';
            const params = [month, year].filter(p => p).join('&');
            const query = params ? `?${params}` : '';
            
            pdfExportBtn.href = `/admin/reports/revenue/export-pdf${query}`;
            excelExportBtn.href = `/admin/reports/revenue/export-excel${query}`;
        }
        
        // Function to fetch filtered data from server
        function fetchFilteredData(month, year) {
            const filterLoading = document.getElementById('filterLoading');
            const summaryCards = document.getElementById('summaryCards');
            const chartSection = document.getElementById('chartSection');
            const tableBody = document.getElementById('revenueTableBody');
            const revenueChart = document.getElementById('revenueChart');
            
            // Show loading
            filterLoading.style.display = 'block';
            summaryCards.style.opacity = '0.5';
            chartSection.style.display = 'none';
            if (revenueChart) {
                revenueChart.style.display = 'none';
            }
            tableBody.innerHTML = `
                <tr>
                    <td colspan="5" class="text-center py-4">
                        <div class="spinner-border spinner-border-sm text-primary me-2" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        Loading filtered data...
                    </td>
                </tr>`;
            
            // Update current filter
            currentFilter = { month, year };
            updateExportLinks();
            
            // Fetch filtered data from server
            fetch(`/admin/reports/revenue/filter`, {
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
                console.log('Filtered data received:', filteredData);
                
                // Hide loading
                filterLoading.style.display = 'none';
                summaryCards.style.opacity = '1';
                
                // Update summary cards
                document.getElementById('totalSales').textContent = `RM ${parseFloat(filteredData.summary.total_sales || 0).toFixed(2)}`;
                //document.getElementById('totalIncome').textContent = `RM ${parseFloat(filteredData.summary.total_income || 0).toFixed(2)}`;
                document.getElementById('avgDuration').textContent = `${parseFloat(filteredData.summary.avg_duration || 0).toFixed(1)} hrs`;
                document.getElementById('completedPayments').textContent = filteredData.summary.completed_payments || 0;
                
                // Update table
                if (!filteredData.data || filteredData.data.length === 0) {
                    tableBody.innerHTML = `
                        <tr>
                            <td colspan="5" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="fas fa-search me-2"></i>
                                    No revenue data found for the selected filter.
                                </div>
                            </td>
                        </tr>`;
                } else {
                    let newHtml = '';
                    filteredData.data.forEach(row => {
                        newHtml += `
                            <tr>
                                <td>${row.vehicleName || '-'}</td>
                                <td>${row.bookingID || '-'}</td>
                                <td>${row.duration || '0'}</td>
                                <td>${row.paymentType || '-'}</td>
                                <td>RM ${parseFloat(row.totalAmount || 0).toFixed(2)}</td>
                            </tr>`;
                    });
                    tableBody.innerHTML = newHtml;
                }
                
                // Update chart
                if (filteredData.chart && filteredData.chart.labels && filteredData.chart.labels.length > 0) {
                    chartSection.style.display = 'block';
                    if (revenueChart) {
                        revenueChart.style.display = 'block';
                        // Destroy existing chart if exists
                        if (window.revenueChartInstance) {
                            window.revenueChartInstance.destroy();
                        }
                        initializeRevenueChart(filteredData.chart);
                    }
                } else {
                    chartSection.style.display = 'none';
                    if (revenueChart) {
                        revenueChart.style.display = 'none';
                    }
                }
            })
            .catch(error => {
                console.error('Filter error:', error);
                filterLoading.style.display = 'none';
                summaryCards.style.opacity = '1';
                tableBody.innerHTML = `
                    <tr>
                        <td colspan="5" class="text-center py-4 text-danger">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Error loading filtered data: ${error.message}
                        </td>
                    </tr>`;
            });
        }
        
        // Function to initialize chart
        function initializeRevenueChart(chartData) {
            const ctxRevenue = document.getElementById('revenueChart').getContext('2d');
            window.revenueChartInstance = new Chart(ctxRevenue, {
                type: 'bar',
                data: {
                    labels: chartData.labels,
                    datasets: [{
                        label: 'Revenue (RM)',
                        data: chartData.data,
                        backgroundColor: '#dc3545',
                        borderColor: '#dc3545',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return 'RM ' + context.parsed.y.toFixed(2);
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return 'RM ' + value;
                                }
                            }
                        }
                    }
                }
            });
        }
        
        // Event listeners
        if (applyBtn) {
            applyBtn.addEventListener('click', function() {
                const month = monthSelect ? monthSelect.value : '';
                const year = yearSelect ? yearSelect.value : '';
                fetchFilteredData(month, year);
            });
        }
        
        if (resetBtn) {
            resetBtn.addEventListener('click', function() {
                if (monthSelect) monthSelect.value = '';
                if (yearSelect) yearSelect.value = '';
                fetchFilteredData('', '');
            });
        }
        
        /*if (refreshBtn) {
            refreshBtn.addEventListener('click', function() {
                const month = monthSelect ? monthSelect.value : '';
                const year = yearSelect ? yearSelect.value : '';
                fetchFilteredData(month, year);
            });
        }
        
        // Auto-apply filter when dropdown changes
        if (monthSelect) {
            monthSelect.addEventListener('change', function() {
                const month = monthSelect.value;
                const year = yearSelect ? yearSelect.value : '';
                fetchFilteredData(month, year);
            });
        }
        
        if (yearSelect) {
            yearSelect.addEventListener('change', function() {
                const month = monthSelect ? monthSelect.value : '';
                const year = yearSelect.value;
                fetchFilteredData(month, year);
            });
        } */
        
        // Initialize export links
        updateExportLinks();
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
    } // <-- INI PENUTUP renderTopCollegeReport YANG BETUL
    
    function renderTotalBookingReport(data) {
    let html = `
        <h5 class="mb-3">Total Booking Report</h5>
        
        <!-- Filter Form -->
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <select id="filterMonth" class="form-select">
                    <option value="">All Months</option>
                    <option value="1">January</option>
                    <option value="2">February</option>
                    <option value="3">March</option>
                    <option value="4">April</option>
                    <option value="5">May</option>
                    <option value="6">June</option>
                    <option value="7">July</option>
                    <option value="8">August</option>
                    <option value="9">September</option>
                    <option value="10">October</option>
                    <option value="11">November</option>
                    <option value="12">December</option>
                </select>
            </div>
            <div class="col-md-3">
                <select id="filterYear" class="form-select">
                    <option value="">All Years</option>`;
    
    // Generate year options
    const currentYear = new Date().getFullYear();
    for (let year = currentYear; year >= currentYear - 5; year--) {
        html += `<option value="${year}">${year}</option>`;
    }
    
    html += `
                </select>
            </div>
            <div class="col-md-2">
                <button id="applyFilter" class="btn btn-primary w-100">
                    <i class="fas fa-filter me-2"></i> Filter
                </button>
            </div>
            <div class="col-md-2">
                <button id="resetFilter" class="btn btn-outline-secondary w-100">
                    <i class="fas fa-redo me-1"></i> Reset
                </button>
            </div>
            
        </div>
        
        <!-- Summary Cards - HANYA TOTAL SAHAJA -->
        <div class="row mb-4" id="summaryCards">
            <div class="col-md-12">
                <div class="card text-center bg-light">
                    <div class="card-body">
                        <h6 class="text-muted">Total Bookings</h6>
                        <p class="fs-4 mb-0 text-dark" id="totalCount">${data.summary.total || 0}</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Export Buttons -->
        <div class="mb-4 d-flex justify-content-between align-items-center">
            <div>
                <a href="/admin/reports/total_booking/export-pdf" class="btn btn-danger me-2" id="pdfExportBtn">
                    <i class="fas fa-file-pdf me-1"></i> Export PDF
                </a>
                <a href="/admin/reports/total_booking/export-excel" class="btn btn-success" id="excelExportBtn">
                    <i class="fas fa-file-excel me-1"></i> Export Excel
                </a>
            </div>
            <div class="text-muted">
                <small><i class="fas fa-info-circle me-1"></i> Export includes current filter</small>
            </div>
        </div>
        
        <!-- Loading for Filter -->
        <div id="filterLoading" class="text-center py-3" style="display: none;">
            <div class="spinner-border spinner-border-sm text-primary me-2" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <span class="text-muted">Loading filtered data...</span>
        </div>
        
        <!-- Table -->
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Booking ID</th>
                        <th>User ID</th>
                        <th>Customer Name</th>
                        <th>Vehicle Name</th>
                        <th>Booking Period</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody id="bookingTableBody">`;
    
    if (data.data && data.data.length > 0) {
        data.data.forEach(row => {
            // Format dates seperti dalam Blade template
            const pickupDateTime = formatDateTime(row.pickup_dateTime);
            const returnDateTime = formatDateTime(row.return_dateTime);
            
            // Get status badge color - TUKAR "completed" kepada "successful"
            let statusColor = 'secondary';
            let displayStatus = row.bookingStatus;
            
            if (row.bookingStatus) {
                const status = row.bookingStatus.toLowerCase();
                if (status === 'completed' || status === 'successful') {
                    statusColor = 'success';
                    displayStatus = 'successful'; // TUKAR DISINI
                } else if (status === 'pending') {
                    statusColor = 'warning';
                } else if (status === 'cancelled' || status === 'rejected') {
                    statusColor = 'danger';
                }
            }
            
            html += `
                <tr>
                    <td>${row.bookingID || '-'}</td>
                    <td>${row.userID || '-'}</td>
                    <td>${row.name || '-'}</td>
                    <td>${row.vehicleName || '-'}</td>
                    <td>${pickupDateTime} - ${returnDateTime}</td>
                    <td>
                        <span class="badge bg-${statusColor}">
                            ${displayStatus ? displayStatus.charAt(0).toUpperCase() + displayStatus.slice(1) : '-'}
                        </span>
                    </td>
                </tr>`;
        });
    } else {
        html += `
            <tr>
                <td colspan="6" class="text-center py-4">
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
        </div>`;
    
    reportContent.innerHTML = html;
    
    // Add event listeners for filter buttons
    const applyBtn = document.getElementById('applyFilter');
    const resetBtn = document.getElementById('resetFilter');
    const monthSelect = document.getElementById('filterMonth');
    const yearSelect = document.getElementById('filterYear');
    const pdfExportBtn = document.getElementById('pdfExportBtn');
    const excelExportBtn = document.getElementById('excelExportBtn');
    
    let currentFilter = {
        month: '',
        year: ''
    };
    
    // Function to update export links with current filter
    function updateExportLinks() {
        const month = currentFilter.month ? `month=${currentFilter.month}` : '';
        const year = currentFilter.year ? `year=${currentFilter.year}` : '';
        const params = [month, year].filter(p => p).join('&');
        const query = params ? `?${params}` : '';
        
        pdfExportBtn.href = `/admin/reports/total_booking/export-pdf${query}`;
        excelExportBtn.href = `/admin/reports/total_booking/export-excel${query}`;
    }
    
    // Function to fetch filtered data from server
    function fetchFilteredData(month, year) {
        const filterLoading = document.getElementById('filterLoading');
        const summaryCards = document.getElementById('summaryCards');
        const tableBody = document.getElementById('bookingTableBody');
        
        // Show loading
        filterLoading.style.display = 'block';
        summaryCards.style.opacity = '0.5';
        tableBody.innerHTML = `
            <tr>
                <td colspan="6" class="text-center py-4">
                    <div class="spinner-border spinner-border-sm text-primary me-2" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    Loading filtered data...
                </td>
            </tr>`;
        
        // Update current filter
        currentFilter = { month, year };
        updateExportLinks();
        
        // Fetch filtered data from server
        fetch(`/admin/reports/total_booking/filter`, {
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
            console.log('Filtered booking data received:', filteredData);
            
            // Hide loading
            filterLoading.style.display = 'none';
            summaryCards.style.opacity = '1';
            
            // Update summary card - HANYA TOTAL
            document.getElementById('totalCount').textContent = filteredData.summary.total || 0;
            
            // Update table
            if (!filteredData.data || filteredData.data.length === 0) {
                tableBody.innerHTML = `
                    <tr>
                        <td colspan="6" class="text-center py-4">
                            <div class="text-muted">
                                <i class="fas fa-search me-2"></i>
                                No booking data found for the selected filter.
                            </div>
                        </td>
                    </tr>`;
            } else {
                let newHtml = '';
                filteredData.data.forEach(row => {
                    // Format dates
                    const pickupDateTime = formatDateTime(row.pickup_dateTime);
                    const returnDateTime = formatDateTime(row.return_dateTime);
                    
                    // Get status badge color
                    let statusColor = 'secondary';
                    let displayStatus = row.bookingStatus;
                    
                    if (row.bookingStatus) {
                        const status = row.bookingStatus.toLowerCase();
                        if (status === 'completed' || status === 'successful') {
                            statusColor = 'success';
                            displayStatus = 'successful';
                        } else if (status === 'pending') {
                            statusColor = 'warning';
                        } else if (status === 'cancelled' || status === 'rejected') {
                            statusColor = 'danger';
                        }
                    }
                    
                    newHtml += `
                        <tr>
                            <td>${row.bookingID || '-'}</td>
                            <td>${row.userID || '-'}</td>
                            <td>${row.name || '-'}</td>
                            <td>${row.vehicleName || '-'}</td>
                            <td>${pickupDateTime} - ${returnDateTime}</td>
                            <td>
                                <span class="badge bg-${statusColor}">
                                    ${displayStatus ? displayStatus.charAt(0).toUpperCase() + displayStatus.slice(1) : '-'}
                                </span>
                            </td>
                        </tr>`;
                });
                tableBody.innerHTML = newHtml;
            }
        })
        .catch(error => {
            console.error('Filter error:', error);
            filterLoading.style.display = 'none';
            summaryCards.style.opacity = '1';
            tableBody.innerHTML = `
                <tr>
                    <td colspan="6" class="text-center py-4 text-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Error loading filtered data: ${error.message}
                    </td>
                </tr>`;
        });
    }
    
    // Event listeners
    if (applyBtn) {
        applyBtn.addEventListener('click', function() {
            const month = monthSelect ? monthSelect.value : '';
            const year = yearSelect ? yearSelect.value : '';
            fetchFilteredData(month, year);
        });
    }
    
    if (resetBtn) {
        resetBtn.addEventListener('click', function() {
            if (monthSelect) monthSelect.value = '';
            if (yearSelect) yearSelect.value = '';
            fetchFilteredData('', '');
        });
    }
    
    // Initialize export links
    updateExportLinks();
}
    
    function renderBlacklistedReport(data) {
        console.log('Blacklist data received:', data);
        
        let html = `
            <h5 class="mb-3"><i class="fa fa-user-times me-2"></i>Blacklisted Customers</h5>
            
            <!-- Summary Statistics - HANYA TOTAL SAHAJA -->
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="card text-center bg-light">
                        <div class="card-body">
                            <h6 class="text-muted">Total Blacklisted Customers</h6>
                            <p class="fs-4 mb-0 text-dark">${data.summary?.total || 0}</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Filter Form -->
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <input type="text" id="searchInput" class="form-control" 
                        placeholder="Search name, email, ID, IC, or phone...">
                </div>
                <div class="col-md-4">
                    <input type="text" id="reasonInput" class="form-control" 
                        placeholder="Filter by reason...">
                </div>
                <div class="col-md-2">
                    <button id="applyFilterBtn" class="btn btn-primary w-100">
                        <i class="fas fa-filter me-2"></i> Filter
                    </button>
                </div>
                <div class="col-md-2">
                    <button id="resetFilterBtn" class="btn btn-outline-secondary w-100">
                        <i class="fas fa-redo me-1"></i>Reset
                    </button>
                </div>
            </div>
            
            <!-- Export Buttons -->
            <div class="mb-4">
                <a href="/admin/reports/blacklisted/export-pdf" class="btn btn-danger me-2">
                    <i class="fas fa-file-pdf me-1"></i> Export PDF
                </a>
                <a href="/admin/reports/blacklisted/export-excel" class="btn btn-success">
                    <i class="fas fa-file-excel me-1"></i> Export Excel
                </a>
            </div>
            
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Blacklist ID</th>
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
                    <tbody id="blacklistTableBody">`;
        
        if (data.data && data.data.length > 0) {
            data.data.forEach(row => {
                console.log('Row data:', row); // Debug
                
                html += `
                    <tr>
                        <td><code>${row.blacklistID || 'N/A'}</code></td>
                        <td><span class="badge bg-dark">${row.userID || 'N/A'}</span></td>
                        <td><div class="fw-bold">${row.name || 'N/A'}</div></td>
                        <td><small>${row.email || 'N/A'}</small></td>
                        <td>
                            ${row.noHP && row.noHP !== 'NULL' && row.noHP.trim() !== '' ? 
                                row.noHP : 
                                '<span class="text-muted">N/A</span>'
                            }
                        </td>
                        <td>
                            ${row.noIC && row.noIC !== 'NULL' && row.noIC.trim() !== '' ? 
                                row.noIC : 
                                '<span class="text-muted">N/A</span>'
                            }
                        </td>
                        <td>
                            <span class="badge bg-primary">
                                Student
                            </span>
                        </td>
                        <td>
                            <div style="max-width: 250px; max-height: 60px; overflow-y: auto; padding: 5px; background: #f8f9fa; border-radius: 4px; border-left: 3px solid #dc3545;">
                                <small>${row.reason || 'Not specified'}</small>
                            </div>
                        </td>
                        <td>
                            ${row.admin_name ? 
                                `<div><div class="fw-bold">${row.admin_name}</div><small class="text-muted">ID: ${row.adminID}</small></div>` : 
                                '<span class="text-muted">N/A</span>'
                            }
                        </td>
                    </tr>`;
            });
        } else {
            html += `
                <tr>
                    <td colspan="9" class="text-center py-5">
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
            </div>`;
        
        reportContent.innerHTML = html;
        
        // Add event listeners for filter buttons
        const applyBtn = document.getElementById('applyFilterBtn');
        const resetBtn = document.getElementById('resetFilterBtn');
        const searchInput = document.getElementById('searchInput');
        const reasonInput = document.getElementById('reasonInput');
        
        if (applyBtn && resetBtn) {
            applyBtn.addEventListener('click', function() {
                applyBlacklistFilter();
            });
            
            resetBtn.addEventListener('click', function() {
                if (searchInput) searchInput.value = '';
                if (reasonInput) reasonInput.value = '';
                applyBlacklistFilter();
            });
            
            // Enter key for search
            if (searchInput) {
                searchInput.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') applyBlacklistFilter();
                });
            }
            if (reasonInput) {
                reasonInput.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') applyBlacklistFilter();
                });
            }
        }
        
        function applyBlacklistFilter() {
            const search = searchInput ? searchInput.value : '';
            const reason = reasonInput ? reasonInput.value : '';
            
            const tbody = document.getElementById('blacklistTableBody');
            if (!tbody) return;
            
            tbody.innerHTML = `
                <tr>
                    <td colspan="9" class="text-center py-4">
                        <div class="spinner-border spinner-border-sm text-danger me-2" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        Loading filtered data...
                    </td>
                </tr>`;
            
            // Use POST method with JSON body
            fetch(`/admin/reports/blacklisted/filter`, {
                method: 'POST',
                headers: { 
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    search: search || '',
                    reason: reason || ''
                })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                return response.json();
            })
            .then(filteredData => {
                console.log('Filtered data:', filteredData);
                
                if (!filteredData.data || filteredData.data.length === 0) {
                    tbody.innerHTML = `
                        <tr>
                            <td colspan="9" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="fa fa-user-times fa-3x mb-3"></i>
                                    <h5 class="mb-2">No Records Found</h5>
                                    <p class="mb-0">No records match your filter criteria.</p>
                                </div>
                            </td>
                        </tr>`;
                } else {
                    let newHtml = '';
                    filteredData.data.forEach(row => {
                        newHtml += `
                            <tr>
                                <td><code>${row.blacklistID || 'N/A'}</code></td>
                                <td><span class="badge bg-dark">${row.userID || 'N/A'}</span></td>
                                <td><div class="fw-bold">${row.name || 'N/A'}</div></td>
                                <td><small>${row.email || 'N/A'}</small></td>
                                <td>
                                    ${row.noHP && row.noHP !== 'NULL' && row.noHP.trim() !== '' ? 
                                        row.noHP : 
                                        '<span class="text-muted">N/A</span>'
                                    }
                                </td>
                                <td>
                                    ${row.noIC && row.noIC !== 'NULL' && row.noIC.trim() !== '' ? 
                                        row.noIC : 
                                        '<span class="text-muted">N/A</span>'
                                    }
                                </td>
                                <td>
                                    <span class="badge bg-primary">
                                        Student
                                    </span>
                                </td>
                                <td>
                                    <div style="max-width: 250px; max-height: 60px; overflow-y: auto; padding: 5px; background: #f8f9fa; border-radius: 4px; border-left: 3px solid #dc3545;">
                                        <small>${row.reason || 'Not specified'}</small>
                                    </div>
                                </td>
                                <td>
                                    ${row.admin_name ? 
                                        `<div><div class="fw-bold">${row.admin_name}</div><small class="text-muted">ID: ${row.adminID}</small></div>` : 
                                        '<span class="text-muted">N/A</span>'
                                    }
                                </td>
                            </tr>`;
                    });
                    tbody.innerHTML = newHtml;
                    
                    // Update summary stats - HANYA TOTAL
                    if (filteredData.summary) {
                        document.querySelector('.card.text-center').querySelector('p').textContent = filteredData.summary.total || 0;
                    }
                }
            })
            .catch(error => {
                console.error('Filter error:', error);
                tbody.innerHTML = `
                    <tr>
                        <td colspan="9" class="text-center py-4 text-danger">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Error loading filtered data: ${error.message}
                        </td>
                    </tr>`;
            });
        }
    }
});

</script>
@endpush