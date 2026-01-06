<div class="metric-card mb-3">
    <div class="metric-title">Total Revenue (Completed Bookings)</div>
    <div class="metric-value">RM {{ number_format($data, 2) }}</div>
</div>

<canvas id="revenueChart" height="100"></canvas>

<script>
    const ctxRevenue = document.getElementById('revenueChart').getContext('2d');
    new Chart(ctxRevenue, {
        type: 'bar',
        data: {
            labels: ['Revenue'],
            datasets: [{
                label: 'Total Revenue',
                data: [{{ $data }}],
                backgroundColor: '#dc3545'
            }]
        }
    });
</script>
