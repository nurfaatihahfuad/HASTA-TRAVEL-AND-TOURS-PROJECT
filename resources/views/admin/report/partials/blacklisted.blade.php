@if($data->isEmpty())
    <p class="text-muted">No blacklisted customers found.</p>
@else
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>User ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Reason</th>
                <th>Date Blacklisted</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $user)
            <tr>
                <td>{{ $user->userID }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->blacklist_reason ?? 'N/A' }}</td>
                <td>{{ $user->blacklisted_at ?? 'N/A' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endif
