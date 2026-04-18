@extends('layouts.app')
@section('title', 'Edit Event')
@section('content')
    <main class="max-w-3xl mx-auto px-4 py-10 sm:px-6 lg:px-8">
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 sm:p-8">
            <div class="mb-8 flex items-center justify-between gap-4">
                <h1 class="text-2xl sm:text-3xl font-semibold tracking-tight">Edit Event</h1>
                <a href="{{ route('events.show', $event) }}" class="inline-flex items-center rounded-lg border border-gray-300 px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100">Back</a>
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

            <form action="{{ route('events.update', $event) }}" method="POST" class="space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <label for="event_name" class="mb-1.5 block text-sm font-medium text-gray-700">Event Name</label>
                    <input type="text" name="event_name" id="event_name" value="{{ old('event_name', $event->event_name) }}" required class="block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                <div>
                    <label for="event_descriptions" class="mb-1.5 block text-sm font-medium text-gray-700">Description</label>
                    <textarea name="event_descriptions" id="event_descriptions" rows="4" class="block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('event_descriptions', $event->event_descriptions) }}</textarea>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label for="event_fee" class="mb-1.5 block text-sm font-medium text-gray-700">Event Fee (cents)</label>
                        <input type="number" name="event_fee" id="event_fee" value="{{ old('event_fee', $event->event_fee) }}" min="0" max="99999" required class="block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <p class="mt-1 text-xs text-gray-500">Enter fee in cents (e.g. 1000 = $10.00)</p>
                    </div>

                    <div>
                        <label for="max_participants" class="mb-1.5 block text-sm font-medium text-gray-700">Max Participants</label>
                        <input type="number" name="max_participants" id="max_participants" value="{{ old('max_participants', $event->max_participants) }}" min="1" required class="block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                </div>

                <div>
                    <label for="event_date" class="mb-1.5 block text-sm font-medium text-gray-700">Event Date & Time</label>
                    <input type="datetime-local" name="event_date" id="event_date"
                        value="{{ old('event_date', \Carbon\Carbon::parse($event->event_date)->format('Y-m-d\TH:i')) }}"
                        required class="block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                <hr class="border-gray-200">
                <p class="text-sm text-gray-500">Reservation details — the table slot reserved for this event.</p>

                <div>
                    <label for="num_guests" class="mb-1.5 block text-sm font-medium text-gray-700">Number of Guests</label>
                    <input type="number" name="num_guests" id="num_guests"
                        value="{{ old('num_guests', $currentNumGuests) }}" min="1" required readonly
                        class="block w-full rounded-lg border border-gray-200 bg-gray-100 px-3 py-2.5 text-sm text-gray-600 cursor-not-allowed">
                    <p class="mt-1 text-xs text-gray-500">Automatically set to Max Participants.</p>
                </div>

                <div>
                    <label for="table_id" class="mb-1.5 block text-sm font-medium text-gray-700">Table(s)</label>
                    <select name="table_id[]" id="table_id" multiple required size="5"
                        class="block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @foreach($tables as $table)
                            <option value="{{ $table->table_id }}"
                                {{ in_array((string) $table->table_id, array_map('strval', (array) old('table_id', $currentTableIds))) ? 'selected' : '' }}>
                                {{ $table->name }} (Capacity: {{ $table->capacity }})
                            </option>
                        @endforeach
                    </select>
                    <p class="mt-1 text-xs text-gray-500">Hold Ctrl / Cmd to select multiple tables.</p>
                </div>

                <div>
                    <label for="time_slots_id" class="mb-1.5 block text-sm font-medium text-gray-700">Time Slot(s)</label>
                    <select name="time_slots_id[]" id="time_slots_id" multiple required size="5"
                        class="block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @foreach($timeSlots as $slot)
                            <option value="{{ $slot->time_slots_id }}"
                                {{ in_array((string) $slot->time_slots_id, array_map('strval', (array) old('time_slots_id', $currentTimeSlotIds))) ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::parse($slot->start_time)->format('h:i A') }} – {{ \Carbon\Carbon::parse($slot->end_time)->format('h:i A') }}
                            </option>
                        @endforeach
                    </select>
                    <p class="mt-1 text-xs text-gray-500">Hold Ctrl / Cmd to select multiple slots.</p>
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="submit" class="inline-flex items-center rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        Save Changes
                    </button>
                    <a href="{{ route('events.show', $event) }}" class="inline-flex items-center rounded-lg border border-gray-300 px-5 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-100">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </main>
@endsection
@push('scripts')
    <script>
        (function () {
            const tableSelect   = document.getElementById('table_id');
            const dateInput     = document.getElementById('event_date');
            const slotSelect    = document.getElementById('time_slots_id');
            const bookedUrl     = '{{ route('api.booked-slots') }}';
            const eventId       = {{ $event->event_id }};

            async function refreshSlots() {
                const selectedTables = Array.from(tableSelect.selectedOptions).map(o => o.value);
                const raw = dateInput.value;
                if (!selectedTables.length || !raw) return;

                const date = raw.split('T')[0];

                let bookedSet = new Set();
                await Promise.all(selectedTables.map(async tableId => {
                    try {
                        const res = await fetch(`${bookedUrl}?table_id=${encodeURIComponent(tableId)}&date=${encodeURIComponent(date)}&exclude_event_id=${encodeURIComponent(eventId)}`);
                        const data = await res.json();
                        data.forEach(id => bookedSet.add(String(id)));
                    } catch (_) {}
                }));

                Array.from(slotSelect.options).forEach(opt => {
                    const taken = bookedSet.has(opt.value);
                    opt.hidden   = taken;
                    opt.disabled = taken;
                    if (taken) opt.selected = false;
                });
            }

            tableSelect.addEventListener('change', refreshSlots);
            dateInput.addEventListener('change', refreshSlots);
            refreshSlots();
        })();

        // Sync num_guests to max_participants
        (function () {
            const maxInput    = document.getElementById('max_participants');
            const guestsInput = document.getElementById('num_guests');
            const sync = () => { guestsInput.value = maxInput.value || 1; };
            maxInput.addEventListener('input', sync);
            sync();
        })();
    </script>
@endpush
