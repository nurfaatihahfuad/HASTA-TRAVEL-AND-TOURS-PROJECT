<table class="table table-bordered">
    <thead>
        <tr>
            <th>College</th>
            <th>Total Bookings</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $row)
        <tr>
            <td>{{ $row->college }}</td>
            <td>{{ $row->total }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<canvas id="collegeChart" height="100"></canvas>

<script>
    const ctxCollege = document.getElementById('collegeChart').getContext('2d');
    new Chart(ctxCollege, {
        type: 'bar',
        data: {
            labels: @json($data->pluck('college')),
            datasets: [{
                label: 'Total Bookings',
                data: @json($data->pluck('total')),
                backgroundColor: '#dc3545'
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } }
        }
    });
</script>
