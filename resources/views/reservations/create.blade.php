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

            @if(session('success'))
                <div class="mb-6 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                    {{ session('success') }}
                </div>
            @endif

            <div class="mb-6 rounded-lg border border-indigo-200 bg-indigo-50 px-4 py-3 text-sm text-indigo-700">
                Each reservation earns <span class="font-semibold">10 loyalty tokens</span>.
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

                <div id="member-section">
                    <label for="member_id" class="mb-1.5 block text-sm font-medium text-gray-700">Member</label>
                    <select name="member_id" id="member_id" class="block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="" disabled {{ old('member_id') ? '' : 'selected' }}>Select a member</option>
                        @foreach($members as $member)
                            <option value="{{ $member->member_id }}" {{ (string) old('member_id') === (string) $member->member_id ? 'selected' : '' }}>{{ $member->first_name }} {{ $member->last_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="event_id" class="mb-1.5 block text-sm font-medium text-gray-700">Event (Optional)</label>
                    <select name="event_id" id="event_id" class="block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">No event</option>
                        @foreach($events as $event)
                            <option value="{{ $event->event_id }}" {{ (string) old('event_id', $prefillEventId ?? '') === (string) $event->event_id ? 'selected' : '' }}>
                                {{ $event->event_name }} ({{ \Carbon\Carbon::parse($event->event_date)->format('Y-m-d') }})
                            </option>
                        @endforeach
                    </select>
                    <p class="mt-1 text-xs text-gray-500">When an event is selected, this reservation uses a system dummy member. Real users should join from the event join page.</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label for="date" class="mb-1.5 block text-sm font-medium text-gray-700">Date</label>
                        <input type="date" name="date" id="date" value="{{ old('date', $prefillDate ?? '') }}" required class="block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <div>
                        <label for="num_guests" class="mb-1.5 block text-sm font-medium text-gray-700">Number of Guests</label>
                        <input type="number" name="num_guests" id="num_guests" value="{{ old('num_guests') }}" min="1" required class="block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                </div>

                <div>
                    <label for="table_id" class="mb-1.5 block text-sm font-medium text-gray-700">Table</label>
                    <select name="table_id" id="table_id" required class="block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="" disabled {{ old('table_id') ? '' : 'selected' }}>Select a table</option>
                        @foreach($tables as $table)
                            <option value="{{ $table->table_id }}" {{ (string) old('table_id') === (string) $table->table_id ? 'selected' : '' }}>{{ $table->name }} (Capacity: {{ $table->capacity }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="time_slots_id" class="mb-1.5 block text-sm font-medium text-gray-700">Time Slot(s)</label>
                    <p class="mb-2 text-xs text-gray-500">Hold Ctrl (Windows) or Cmd (Mac) to select multiple slots.</p>
                    <select name="time_slots_id[]" id="time_slots_id" required multiple size="5" class="block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @foreach($timeSlots as $timeSlot)
                            <option value="{{ $timeSlot->time_slots_id }}" {{ in_array((string) $timeSlot->time_slots_id, collect(old('time_slots_id', []))->map(fn ($v) => (string) $v)->all(), true) ? 'selected' : '' }}>{{ \Carbon\Carbon::parse($timeSlot->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($timeSlot->end_time)->format('h:i A') }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="notes" class="mb-1.5 block text-sm font-medium text-gray-700">Additional Notes</label>
                    <textarea name="notes" id="notes" rows="4" class="block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Optional notes...">{{ old('notes') }}</textarea>
                </div>

                <div class="pt-2">
                    <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        Create Reservation
                    </button>
                </div>
            </form>
        </div>
    </main>
    <script>
        (function () {
            const eventSelect = document.getElementById('event_id');
            const memberSelect = document.getElementById('member_id');
            const memberSection = document.getElementById('member-section');

            const syncMemberRequirement = () => {
                const hasEvent = eventSelect && eventSelect.value !== '';

                memberSelect.required = !hasEvent;
                memberSelect.disabled = hasEvent;

                if (memberSection) {
                    memberSection.classList.toggle('opacity-60', hasEvent);
                }
            };

            if (eventSelect && memberSelect) {
                eventSelect.addEventListener('change', syncMemberRequirement);
                syncMemberRequirement();
            }
        })();
    </script>
</body>
</html>
