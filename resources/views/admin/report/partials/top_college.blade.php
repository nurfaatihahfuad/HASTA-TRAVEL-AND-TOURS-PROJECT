<div class="section-card">
    <h5 class="mb-3">Top College Booking Report</h5>

    <!-- Optional filter form (month/year) -->
    <form method="GET" action="{{ route('reports.top_college.filter') }}" class="row g-3 mb-4">
        <div class="col-md-3">
            <select name="month" class="form-select">
                <option value="">Select Month</option>
                @for($m = 1; $m <= 12; $m++)
                    <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::create()->month($m)->format('F') }}
                    </option>
                @endfor
            </select>
        </div>
        <div class="col-md-3">
            <select name="year" class="form-select">
                <option value="">Select Year</option>
                @for($y = now()->year; $y >= now()->year - 5; $y--)
                    <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>
                        {{ $y }}
                    </option>
                @endfor
            </select>
        </div>
        <div class="col-md-3">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-filter me-2"></i> Filter
            </button>
        </div>
    </form>

    <!-- Booking Table -->
    <div class="table-responsive">
        <table class="table table-hover">
            <thead class="table-light">
                <tr>
                    <th>Booking ID</th>
                    <th>User ID</th>
                    <th>Name</th>
                    <th>College</th>
                    <th>Vehicle</th>
                    <th>Booking Period</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $row)
                    <tr>
                        <td>{{ $row->bookingID }}</td>
                        <td>{{ $row->userID }}</td>
                        <td>{{ $row->name }}</td>
                        <td>{{ $row->collegeName }}</td>
                        <td>{{ $row->vehicleName }}</td>
                        <td>
                            {{ \Carbon\Carbon::parse($row->pickup_dateTime)->format('d M Y H:i') }}
                            –
                            {{ \Carbon\Carbon::parse($row->return_dateTime)->format('d M Y H:i') }}
                        </td>
                        <td>
                            <span class="badge bg-{{ $row->bookingStatus === 'completed' ? 'success' : ($row->bookingStatus === 'pending' ? 'warning' : 'secondary') }}">
                                {{ ucfirst($row->bookingStatus) }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">
                            <div class="text-muted">
                                <i class="fas fa-info-circle fa-2x mb-2"></i>
                                <p class="mb-0">No bookings found for the selected filter</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
