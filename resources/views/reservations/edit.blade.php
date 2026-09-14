@extends('layouts.app')
@section('title', 'Edit Reservation')
@section('content')
    <main class="max-w-3xl mx-auto px-4 py-10 sm:px-6 lg:px-8">
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 sm:p-8">
            <div class="mb-8 flex items-center justify-between gap-4">
                <h1 class="text-2xl sm:text-3xl font-semibold tracking-tight">Edit Reservation #{{ $reservation->reservation_id }}</h1>
                <a href="{{ route('reservations.show', $reservation) }}" class="inline-flex items-center rounded-lg border border-gray-300 px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100">Back</a>
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

            <form action="{{ route('reservations.update', $reservation) }}" method="POST" class="space-y-5">
                @csrf
                @method('PUT')

                {{-- Member (read-only) --}}
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700">Member</label>
                    <input type="text" disabled value="{{ $reservation->member->first_name }} {{ $reservation->member->last_name }}"
                        class="block w-full rounded-lg border border-gray-200 bg-gray-100 px-3 py-2.5 text-sm text-gray-600 cursor-not-allowed">
                </div>

                {{-- Date --}}
                <div>
                    <label for="date" class="mb-1.5 block text-sm font-medium text-gray-700">Date</label>
                    <div class="rounded-lg border border-gray-200 bg-white p-4 shadow-sm select-none" id="calendar-widget">
                        <div class="flex items-center justify-between mb-3">
                            <button type="button" id="cal-prev" class="rounded p-1 hover:bg-gray-100 text-gray-600">&#8249;</button>
                            <span id="cal-title" class="text-sm font-semibold text-gray-800"></span>
                            <button type="button" id="cal-next" class="rounded p-1 hover:bg-gray-100 text-gray-600">&#8250;</button>
                        </div>
                        <div class="grid grid-cols-7 text-center text-xs font-medium text-gray-500 mb-1">
                            <span>Sun</span><span>Mon</span><span>Tue</span><span>Wed</span><span>Thu</span><span>Fri</span><span>Sat</span>
                        </div>
                        <div id="cal-days" class="grid grid-cols-7 gap-1 text-sm"></div>
                        <p id="cal-selected-label" class="mt-3 text-xs text-indigo-700 font-medium text-center hidden"></p>
                    </div>
                    <input type="date" name="date" id="date"
                        value="{{ old('date', $reservation->date->format('Y-m-d')) }}" required class="sr-only">
                </div>

                {{-- Guests --}}
                <div>
                    <label for="num_guests" class="mb-1.5 block text-sm font-medium text-gray-700">
                        Number of Guests
                        <span id="capacity-hint" class="ml-2 text-xs font-normal text-gray-400 hidden">(max <span id="capacity-val"></span>)</span>
                    </label>
                    <input type="number" name="num_guests" id="num_guests"
                        value="{{ old('num_guests', $reservation->num_guests) }}" min="1" required
                        class="block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <p id="capacity-warning" class="hidden mt-1 text-xs text-red-600">Exceeds the selected table's maximum capacity.</p>
                </div>

                {{-- Table --}}
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700">Table</label>

                    <div class="flex flex-wrap gap-2 mb-3" id="table-filters">
                        <button type="button" data-filter="all" class="filter-btn active rounded-full border border-indigo-500 bg-indigo-50 px-3 py-1 text-xs font-medium text-indigo-700">All</button>
                        @foreach($tables->pluck('type')->unique() as $type)
                        <button type="button" data-filter="{{ $type }}" class="filter-btn rounded-full border border-gray-300 px-3 py-1 text-xs font-medium text-gray-600 hover:border-indigo-400 hover:text-indigo-600">{{ $type }}</button>
                        @endforeach
                    </div>

                    @php
                        $selectedTableId = old('table_id', $currentTableId);
                    @endphp
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3" id="table-cards">
                        @foreach($tables as $table)
                        @php
                            $typeColors = ['Private' => 'bg-purple-100 text-purple-700', 'VIP' => 'bg-yellow-100 text-yellow-700', 'Gaming' => 'bg-blue-100 text-blue-700', 'Standard' => 'bg-gray-100 text-gray-700'];
                            $typeIcons  = ['Private' => '🔒', 'VIP' => '⭐', 'Gaming' => '🎮', 'Standard' => '🪑'];
                            $badgeClass = $typeColors[$table->type] ?? 'bg-gray-100 text-gray-700';
                            $icon       = $typeIcons[$table->type] ?? '🪑';
                        @endphp
                        <label data-type="{{ $table->type }}"
                               data-capacity="{{ $table->capacity }}"
                               data-table-id="{{ $table->table_id }}"
                               class="table-card cursor-pointer rounded-xl border-2 border-gray-200 bg-white overflow-hidden transition hover:border-indigo-400 has-[:checked]:border-indigo-600 has-[:checked]:bg-indigo-50">
                            <input type="radio" name="table_id" value="{{ $table->table_id }}" class="sr-only"
                                {{ (string) $selectedTableId === (string) $table->table_id ? 'checked' : '' }} required>
                            @if($table->photo_url)
                                <img src="{{ asset($table->photo_url) }}" alt="{{ $table->name }}" class="w-full h-36 object-cover">
                            @else
                                <div class="w-full h-36 flex items-center justify-center bg-gray-100 text-4xl">{{ $icon }}</div>
                            @endif
                            <div class="p-3">
                                <div class="flex items-center gap-2 flex-wrap mb-1">
                                    <span class="font-medium text-sm text-gray-900">{{ $table->name }}</span>
                                    <span class="rounded-full px-2 py-0.5 text-xs font-medium {{ $badgeClass }}">{{ $table->type }}</span>
                                </div>
                                <p class="text-xs text-gray-500">Max players: <span class="font-semibold text-gray-700">{{ $table->capacity }}</span></p>
                                @if($table->description)
                                <p class="mt-1 text-xs text-gray-400 truncate">{{ $table->description }}</p>
                                @endif
                            </div>
                        </label>
                        @endforeach
                    </div>
                </div>

                {{-- Time Slots --}}
                <div>
                    <label for="time_slots_id" class="mb-1.5 block text-sm font-medium text-gray-700">Time Slot(s)</label>
                    <p class="mb-2 text-xs text-gray-500">Hold Ctrl (Windows) or Cmd (Mac) to select multiple slots.</p>
                    @php
                        $selectedSlotIds = old('time_slots_id', $currentTimeSlotIds);
                    @endphp
                    <select name="time_slots_id[]" id="time_slots_id" required multiple size="5"
                        class="block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @foreach($timeSlots as $timeSlot)
                            <option value="{{ $timeSlot->time_slots_id }}"
                                {{ in_array((string) $timeSlot->time_slots_id, collect($selectedSlotIds)->map(fn ($v) => (string) $v)->all(), true) ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::parse($timeSlot->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($timeSlot->end_time)->format('h:i A') }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="pt-2 flex flex-wrap gap-3">
                    <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        Save Changes
                    </button>
                    <a href="{{ route('reservations.show', $reservation) }}"
                       class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </main>
@endsection
@push('scripts')
    <script>
        // ── Calendar ────────────────────────────────────────────────────────
        (function () {
            const dateInput = document.getElementById('date');
            const calDays   = document.getElementById('cal-days');
            const calTitle  = document.getElementById('cal-title');
            const calLabel  = document.getElementById('cal-selected-label');
            const btnPrev   = document.getElementById('cal-prev');
            const btnNext   = document.getElementById('cal-next');

            const MONTHS = ['January','February','March','April','May','June','July','August','September','October','November','December'];

            let selected = dateInput.value ? new Date(dateInput.value + 'T00:00:00') : null;
            let current  = selected
                ? new Date(selected.getFullYear(), selected.getMonth(), 1)
                : new Date(new Date().getFullYear(), new Date().getMonth(), 1);

            function toYMD(d) {
                return `${d.getFullYear()}-${String(d.getMonth()+1).padStart(2,'0')}-${String(d.getDate()).padStart(2,'0')}`;
            }

            function render() {
                calTitle.textContent = `${MONTHS[current.getMonth()]} ${current.getFullYear()}`;
                calDays.innerHTML = '';

                const firstDay    = new Date(current.getFullYear(), current.getMonth(), 1).getDay();
                const daysInMonth = new Date(current.getFullYear(), current.getMonth()+1, 0).getDate();

                for (let i = 0; i < firstDay; i++) calDays.insertAdjacentHTML('beforeend', '<div></div>');

                for (let d = 1; d <= daysInMonth; d++) {
                    const date  = new Date(current.getFullYear(), current.getMonth(), d);
                    const ymd   = toYMD(date);
                    const isSel = selected && toYMD(selected) === ymd;

                    let cls = 'rounded-full w-8 h-8 mx-auto flex items-center justify-center text-sm cursor-pointer ';
                    cls += isSel ? 'bg-indigo-600 text-white font-semibold' : 'text-gray-700 hover:bg-indigo-50';

                    const btn = document.createElement('div');
                    btn.className   = cls;
                    btn.textContent = d;
                    btn.addEventListener('click', () => {
                        selected        = date;
                        dateInput.value = ymd;
                        dateInput.dispatchEvent(new Event('change'));
                        calLabel.textContent = `Selected: ${MONTHS[date.getMonth()]} ${d}, ${date.getFullYear()}`;
                        calLabel.classList.remove('hidden');
                        render();
                        refreshSlots();
                    });
                    calDays.appendChild(btn);
                }
            }

            btnPrev.addEventListener('click', () => { current = new Date(current.getFullYear(), current.getMonth()-1, 1); render(); });
            btnNext.addEventListener('click', () => { current = new Date(current.getFullYear(), current.getMonth()+1, 1); render(); });

            if (selected) {
                calLabel.textContent = `Selected: ${MONTHS[selected.getMonth()]} ${selected.getDate()}, ${selected.getFullYear()}`;
                calLabel.classList.remove('hidden');
            }

            render();
        })();

        // ── Table filters ───────────────────────────────────────────────────
        (function () {
            document.querySelectorAll('.filter-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    document.querySelectorAll('.filter-btn').forEach(b => {
                        b.classList.remove('active', 'bg-indigo-50', 'border-indigo-500', 'text-indigo-700');
                        b.classList.add('border-gray-300', 'text-gray-600');
                    });
                    btn.classList.add('active', 'bg-indigo-50', 'border-indigo-500', 'text-indigo-700');
                    btn.classList.remove('border-gray-300', 'text-gray-600');
                    const filter = btn.dataset.filter;
                    document.querySelectorAll('.table-card').forEach(card => {
                        card.style.display = (filter === 'all' || card.dataset.type === filter) ? '' : 'none';
                    });
                });
            });
        })();

        // ── Slot refresh ────────────────────────────────────────────────────
        const tableCards   = document.querySelectorAll('.table-card');
        const slotSelect   = document.getElementById('time_slots_id');
        const dateInput    = document.getElementById('date');
        const bookedUrl    = '{{ route('api.booked-slots') }}';
        const excludeResId = {{ $reservation->reservation_id }};
        const guestsInput  = document.getElementById('num_guests');
        const capacityHint = document.getElementById('capacity-hint');
        const capacityVal  = document.getElementById('capacity-val');
        const capWarning   = document.getElementById('capacity-warning');

        let selectedTableId  = null;
        let selectedCapacity = null;

        function updateCapacityUI(capacity) {
            selectedCapacity = capacity;
            if (capacity) {
                guestsInput.max = capacity;
                capacityVal.textContent = capacity;
                capacityHint.classList.remove('hidden');
            } else {
                guestsInput.removeAttribute('max');
                capacityHint.classList.add('hidden');
            }
            checkCapacity();
        }

        function checkCapacity() {
            if (selectedCapacity && guestsInput.value && parseInt(guestsInput.value) > selectedCapacity) {
                capWarning.classList.remove('hidden');
                guestsInput.setCustomValidity('Exceeds table capacity of ' + selectedCapacity + '.');
            } else {
                capWarning.classList.add('hidden');
                guestsInput.setCustomValidity('');
            }
        }

        guestsInput.addEventListener('input', checkCapacity);

        async function refreshSlots() {
            if (!selectedTableId || !dateInput.value) return;
            let booked = [];
            try {
                const res = await fetch(`${bookedUrl}?table_id=${encodeURIComponent(selectedTableId)}&date=${encodeURIComponent(dateInput.value)}&exclude_reservation_id=${excludeResId}`);
                booked = await res.json();
            } catch (_) {}
            const bookedSet = new Set(booked.map(String));
            Array.from(slotSelect.options).forEach(opt => {
                const taken  = bookedSet.has(opt.value);
                opt.hidden   = taken;
                opt.disabled = taken;
                if (taken) opt.selected = false;
            });
        }

        tableCards.forEach(card => {
            card.addEventListener('click', () => {
                selectedTableId = card.dataset.tableId;
                updateCapacityUI(parseInt(card.dataset.capacity) || null);
                refreshSlots();
            });
        });

        // initialise from pre-selected table
        const preSelected = document.querySelector('.table-card input[type=radio]:checked');
        if (preSelected) {
            const preCard = preSelected.closest('.table-card');
            selectedTableId = preCard.dataset.tableId;
            updateCapacityUI(parseInt(preCard.dataset.capacity) || null);
            refreshSlots();
        }

        dateInput.addEventListener('change', refreshSlots);
    </script>
@endpush
