<!DOCTYPE html>
<html>
<head>
    <title>Reservation Details</title>
</head>
<body>
    <h1>Reservation #{{ $reservation->reservation_id }}</h1>

    <p><strong>Member:</strong> {{ $reservation->member->first_name }} {{ $reservation->member->last_name }}</p>
    <p><strong>Email:</strong> {{ $reservation->member->email }}</p>
    <p><strong>Date:</strong> {{ $reservation->date->format('F j, Y') }}</p>
    <p><strong>Number of Guests:</strong> {{ $reservation->num_guests }}</p>

    <h2>Reserved Slot Details</h2>
    <p><strong>Table:</strong> {{ $reservation->reservedSlots->first()->table->name ?? 'N/A' }}</p>

    <ul>
        @forelse($reservation->reservedSlots as $slot)
            <li>
                <strong>Time Slot:</strong>
                {{ \Carbon\Carbon::parse($slot->timeSlot->start_time)->format('h:i A') }}
                to
                {{ \Carbon\Carbon::parse($slot->timeSlot->end_time)->format('h:i A') }}
            </li>
        @empty
            <li>No time slots were reserved.</li>
        @endforelse
    </ul>

    <a href="{{ route('reservations.index') }}">Back to Reservations List</a>
</body>
</html>
