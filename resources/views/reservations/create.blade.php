<!DOCTYPE html>
<html>
<head>
    <title>Create Reservation</title>
</head>
<body>
    <h1>Create a New Reservation</h1>

    @if ($errors->any())
        <div>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('reservations.store') }}" method="POST">
        @csrf
        <div>
            <label for="member_id">Member:</label>
            <select name="member_id" id="member_id" required>
                @foreach($members as $member)
                    <option value="{{ $member->member_id }}">{{ $member->first_name }} {{ $member->last_name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="date">Date:</label>
            <input type="date" name="date" id="date" required>
        </div>
        <div>
            <label for="num_guests">Number of Guests:</label>
            <input type="number" name="num_guests" id="num_guests" min="1" required>
        </div>
        <div>
            <label for="table_id">Table:</label>
            <select name="table_id" id="table_id" required>
                @foreach($tables as $table)
                    <option value="{{ $table->table_id }}">{{ $table->name }} (Capacity: {{ $table->capacity }})</option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="time_slots_id">Time Slot(s):</label>
            <small>(Hold Ctrl or Cmd to select multiple)</small>
            <select name="time_slots_id[]" id="time_slots_id" required multiple size="5">
                @foreach($timeSlots as $timeSlot)
                    <option value="{{ $timeSlot->time_slots_id }}">{{ \Carbon\Carbon::parse($timeSlot->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($timeSlot->end_time)->format('h:i A') }}</option>
                @endforeach
            </select>
        </div>
         <div>
            <label for="notes">Additional Notes:</label>
            <textarea name="notes" id="notes" rows="4"></textarea>
        <button type="submit">Create Reservation</button>
    </form>
</body>
</html>
