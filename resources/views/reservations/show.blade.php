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
    @foreach($reservation->reservedSlots as $slot)
        <p><strong>Table:</strong> {{ $slot->table->name }}</p>
        <p><strong>Time Slot:</strong> {{ $slot->timeSlot->start_time }} to {{ $slot->timeSlot->end_time }}</p>
    @endforeach

    <a href="{{ route('reservations.index') }}">Back to Reservations List</a>
</body>
</html>
