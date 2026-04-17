<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reservations</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 text-gray-900 min-h-screen">
    <main class="max-w-6xl mx-auto px-4 py-10 sm:px-6 lg:px-8">
        <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <h1 class="text-2xl sm:text-3xl font-semibold tracking-tight">All Reservations</h1>
            <a href="{{ route('reservations.create') }}" class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-indigo-700">Create New Reservation</a>
        </div>

        @if(session('success'))
            <div class="mb-6 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-700 text-sm">
                {{ session('success') }}
            </div>
        @endif

        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">ID</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">Member</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">Date</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">Guests</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">Table</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">Time Slot</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($reservations as $reservation)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-sm text-gray-700">#{{ $reservation->reservation_id }}</td>
                                <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $reservation->member->first_name }} {{ $reservation->member->last_name }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700">{{ $reservation->date->format('Y-m-d') }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700">{{ $reservation->num_guests }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700">
                                    @foreach($reservation->reservedSlots as $slot)
                                        <div>{{ $slot->table->name }}</div>
                                    @endforeach
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-700">
                                    @foreach($reservation->reservedSlots as $slot)
                                        <div>{{ \Carbon\Carbon::parse($slot->timeSlot->start_time)->format('h:i A') }}</div>
                                    @endforeach
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    <a href="{{ route('reservations.show', $reservation) }}" class="inline-flex items-center rounded-lg border border-gray-300 px-3 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-100">View</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-8 text-center text-sm text-gray-500">No reservations found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</body>
</html>
