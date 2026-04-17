<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Create Reservation</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 text-gray-900 min-h-screen">
    <main class="max-w-3xl mx-auto px-4 py-10 sm:px-6 lg:px-8">
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 sm:p-8">
            <div class="mb-8 flex items-center justify-between gap-4">
                <h1 class="text-2xl sm:text-3xl font-semibold tracking-tight">Create a New Reservation</h1>
                <a href="{{ route('reservations.index') }}" class="inline-flex items-center rounded-lg border border-gray-300 px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100">Back</a>
            </div>

            @if ($errors->any())
                <div class="mb-6 rounded-lg border border-red-200 bg-red-50 p-4 text-red-700">
                    <ul class="list-disc pl-5 space-y-1 text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('reservations.store') }}" method="POST" class="space-y-5">
                @csrf

                <div>
                    <label for="member_id" class="mb-1.5 block text-sm font-medium text-gray-700">Member</label>
                    <select name="member_id" id="member_id" required class="block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="" disabled selected>Select a member</option>
                        @foreach($members as $member)
                            <option value="{{ $member->member_id }}">{{ $member->first_name }} {{ $member->last_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label for="date" class="mb-1.5 block text-sm font-medium text-gray-700">Date</label>
                        <input type="date" name="date" id="date" required class="block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <div>
                        <label for="num_guests" class="mb-1.5 block text-sm font-medium text-gray-700">Number of Guests</label>
                        <input type="number" name="num_guests" id="num_guests" min="1" required class="block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                </div>

                <div>
                    <label for="table_id" class="mb-1.5 block text-sm font-medium text-gray-700">Table</label>
                    <select name="table_id" id="table_id" required class="block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="" disabled selected>Select a table</option>
                        @foreach($tables as $table)
                            <option value="{{ $table->table_id }}">{{ $table->name }} (Capacity: {{ $table->capacity }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="time_slots_id" class="mb-1.5 block text-sm font-medium text-gray-700">Time Slot(s)</label>
                    <p class="mb-2 text-xs text-gray-500">Hold Ctrl (Windows) or Cmd (Mac) to select multiple slots.</p>
                    <select name="time_slots_id[]" id="time_slots_id" required multiple size="5" class="block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @foreach($timeSlots as $timeSlot)
                            <option value="{{ $timeSlot->time_slots_id }}">{{ \Carbon\Carbon::parse($timeSlot->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($timeSlot->end_time)->format('h:i A') }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="notes" class="mb-1.5 block text-sm font-medium text-gray-700">Additional Notes</label>
                    <textarea name="notes" id="notes" rows="4" class="block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Optional notes..."></textarea>
                </div>

                <div class="pt-2">
                    <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        Create Reservation
                    </button>
                </div>
            </form>
        </div>
    </main>
</body>
</html>
