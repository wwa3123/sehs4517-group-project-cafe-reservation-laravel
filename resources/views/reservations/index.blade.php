<!DOCTYPE html>
<html>
<head>
    <title>Reservations</title>
</head>
<body>
    <h1>All Reservations</h1>

    @if(session('success'))
        <p>{{ session('success') }}</p>
    @endif

    <a href="{{ route('reservations.create') }}">Create New Reservation</a>

    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Member</th>
                <th>Date</th>
                <th>Guests</th>
                <th>Table</th>
                <th>Time Slot</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reservations as $reservation)
                <tr>
                    <td>{{ $reservation->reservation_id }}</td>
                    <td>{{ $reservation->member->first_name }} {{ $reservation->member->last_name }}</td>
                    <td>{{ $reservation->date->format('Y-m-d') }}</td>
                    <td>{{ $reservation->num_guests }}</td>
                    <td>
                        @foreach($reservation->reservedSlots as $slot)
                            {{ $slot->table->name }}
                        @endforeach
                    </td>
                    <td>
                        @foreach($reservation->reservedSlots as $slot)
                            <div>{{ \Carbon\Carbon::parse($slot->timeSlot->start_time)->format('h:i A') }}</div>
                        @endforeach
                    </td>
                    <td>
                        <a href="{{ route('reservations.show', $reservation) }}">View</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
