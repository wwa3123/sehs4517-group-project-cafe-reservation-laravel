@extends('layouts.app')
@section('title', 'Create Reservation')
@section('content')
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
                    @if(auth()->user()?->role === 'admin')
                    <select name="member_id" id="member_id" class="block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="" disabled {{ old('member_id') ? '' : 'selected' }}>Select a member</option>
                        @foreach($members as $member)
                            <option value="{{ $member->member_id }}" {{ (string) old('member_id') === (string) $member->member_id ? 'selected' : '' }}>{{ $member->first_name }} {{ $member->last_name }}</option>
                        @endforeach
                    </select>
                    @else
                    <input type="text" disabled value="{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}" class="block w-full rounded-lg border border-gray-200 bg-gray-100 px-3 py-2.5 text-sm text-gray-600 cursor-not-allowed">
                    <input type="hidden" name="member_id" value="{{ auth()->user()->member_id }}">
                    @endif
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div class="sm:col-span-2">
                    <label for="date" class="mb-1.5 block text-sm font-medium text-gray-700">Date</label>

                    {{-- Calendar UI --}}
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
                    <input type="date" name="date" id="date" value="{{ old('date', $prefillDate ?? '') }}" required class="sr-only">
                </div>

                    <div class="sm:col-span-2">
                        <label for="num_guests" class="mb-1.5 block text-sm font-medium text-gray-700">Number of Guests</label>
                        <input type="number" name="num_guests" id="num_guests" value="{{ old('num_guests') }}" min="1" required class="block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700">Table</label>

                    {{-- Type filters --}}
                    <div class="flex flex-wrap gap-2 mb-3" id="table-filters">
                        <button type="button" data-filter="all" class="filter-btn active rounded-full border border-indigo-500 bg-indigo-50 px-3 py-1 text-xs font-medium text-indigo-700">All</button>
                        @foreach($tables->pluck('type')->unique() as $type)
                        <button type="button" data-filter="{{ $type }}" class="filter-btn rounded-full border border-gray-300 px-3 py-1 text-xs font-medium text-gray-600 hover:border-indigo-400 hover:text-indigo-600">{{ $type }}</button>
                        @endforeach
                    </div>

                    {{-- Table cards --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3" id="table-cards">
                        @foreach($tables as $table)
                        @php
                            $typeColors = [
                                'Private' => 'bg-purple-100 text-purple-700',
                                'VIP'     => 'bg-yellow-100 text-yellow-700',
                                'Gaming'  => 'bg-blue-100 text-blue-700',
                                'Standard'=> 'bg-gray-100 text-gray-700',
                            ];
                            $typeIcons = [
                                'Private' => '🔒',
                                'VIP'     => '⭐',
                                'Gaming'  => '🎮',
                                'Standard'=> '🪑',
                            ];
                            $badgeClass = $typeColors[$table->type] ?? 'bg-gray-100 text-gray-700';
                            $icon = $typeIcons[$table->type] ?? '🪑';
                        @endphp
                        <label data-type="{{ $table->type }}"
                               data-capacity="{{ $table->capacity }}"
                               data-table-id="{{ $table->table_id }}"
                               class="table-card cursor-pointer rounded-xl border-2 border-gray-200 bg-white p-4 transition hover:border-indigo-400 has-[:checked]:border-indigo-600 has-[:checked]:bg-indigo-50">
                            <input type="radio" name="table_id" value="{{ $table->table_id }}" class="sr-only" {{ (string) old('table_id') === (string) $table->table_id ? 'checked' : '' }} required>
                            <div class="flex items-start gap-3">
                                @if($table->photo_url)
                                    <img src="{{ $table->photo_url }}" alt="{{ $table->name }}" class="h-14 w-14 rounded-lg object-cover flex-shrink-0">
                                @else
                                    <div class="flex h-14 w-14 flex-shrink-0 items-center justify-center rounded-lg bg-gray-100 text-2xl">{{ $icon }}</div>
                                @endif
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <span class="font-medium text-sm text-gray-900">{{ $table->name }}</span>
                                        <span class="rounded-full px-2 py-0.5 text-xs font-medium {{ $badgeClass }}">{{ $table->type }}</span>
                                    </div>
                                    <p class="mt-1 text-xs text-gray-500">Max players: <span class="font-semibold text-gray-700">{{ $table->capacity }}</span></p>
                                    @if($table->description)
                                    <p class="mt-1 text-xs text-gray-400 truncate">{{ $table->description }}</p>
                                    @endif
                                </div>
                            </div>
                        </label>
                        @endforeach
                    </div>
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

                {{-- Loyalty token redemption --}}
                @php $loyaltyPoints = auth()->user()->loyalty_points ?? 0; @endphp
                @if($loyaltyPoints > 0)
                <div class="rounded-xl border border-amber-200 bg-amber-50 p-4 space-y-3">
                    <div class="flex items-center gap-2">
                        <span class="text-lg">🪙</span>
                        <span class="text-sm font-semibold text-amber-800">Redeem Loyalty Tokens</span>
                        <span class="ml-auto rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-medium text-amber-700">{{ $loyaltyPoints }} available</span>
                    </div>
                    <div class="flex flex-wrap gap-2" id="token-tiers">
                        @foreach(\App\Services\LoyaltyRedemptionService::getDiscountTiers($loyaltyPoints) as $tier)
                        <label class="token-tier cursor-pointer rounded-lg border-2 border-amber-200 bg-white px-3 py-2 text-center transition has-[:checked]:border-amber-500 has-[:checked]:bg-amber-100">
                            <input type="radio" name="tokens_to_spend" value="{{ $tier['tokens'] }}" class="sr-only" {{ (string) old('tokens_to_spend') === (string) $tier['tokens'] ? 'checked' : '' }}>
                            <div class="text-xs font-semibold text-amber-900">{{ $tier['tokens'] }} tokens</div>
                            <div class="text-xs text-amber-700">–${{ number_format($tier['discount'], 2) }}</div>
                        </label>
                        @endforeach
                        <label class="token-tier cursor-pointer rounded-lg border-2 border-gray-200 bg-white px-3 py-2 text-center transition has-[:checked]:border-gray-400 has-[:checked]:bg-gray-100">
                            <input type="radio" name="tokens_to_spend" value="0" class="sr-only" {{ old('tokens_to_spend', '0') === '0' ? 'checked' : '' }}>
                            <div class="text-xs font-semibold text-gray-600">No redemption</div>
                            <div class="text-xs text-gray-400">Keep tokens</div>
                        </label>
                    </div>
                </div>
                @endif

                <div class="pt-2">
                    <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        Create Reservation
                    </button>
                </div>
            </form>
        </div>
    </main>
@endsection
@push('scripts')
    <script>
        // ── Games data from server ──────────────────────────────────────────
        const allGames = {!! json_encode($games->map(fn($g) => [
            'title'       => $g->title,
            'category'    => $g->category,
            'min_players' => $g->min_players,
            'max_players' => $g->max_players,
        ])->values()) !!};

        // ── Calendar ────────────────────────────────────────────────────────
        (function () {
            const dateInput    = document.getElementById('date');
            const calDays      = document.getElementById('cal-days');
            const calTitle     = document.getElementById('cal-title');
            const calLabel     = document.getElementById('cal-selected-label');
            const btnPrev      = document.getElementById('cal-prev');
            const btnNext      = document.getElementById('cal-next');

            const today   = new Date(); today.setHours(0,0,0,0);
            let   current = new Date(today.getFullYear(), today.getMonth(), 1);
            let   selected = dateInput.value ? new Date(dateInput.value + 'T00:00:00') : null;

            const MONTHS = ['January','February','March','April','May','June','July','August','September','October','November','December'];

            function toYMD(d) {
                return `${d.getFullYear()}-${String(d.getMonth()+1).padStart(2,'0')}-${String(d.getDate()).padStart(2,'0')}`;
            }

            function render() {
                calTitle.textContent = `${MONTHS[current.getMonth()]} ${current.getFullYear()}`;
                calDays.innerHTML = '';

                const firstDay  = new Date(current.getFullYear(), current.getMonth(), 1).getDay();
                const daysInMonth = new Date(current.getFullYear(), current.getMonth()+1, 0).getDate();

                // blank cells before 1st
                for (let i = 0; i < firstDay; i++) {
                    calDays.insertAdjacentHTML('beforeend', '<div></div>');
                }

                for (let d = 1; d <= daysInMonth; d++) {
                    const date   = new Date(current.getFullYear(), current.getMonth(), d);
                    const isPast = date < today;
                    const ymd    = toYMD(date);
                    const isSel  = selected && toYMD(selected) === ymd;
                    const isToday= toYMD(date) === toYMD(today);

                    let cls = 'rounded-full w-8 h-8 mx-auto flex items-center justify-center text-sm ';
                    if (isPast)       cls += 'text-gray-300 cursor-not-allowed';
                    else if (isSel)   cls += 'bg-indigo-600 text-white font-semibold cursor-pointer';
                    else if (isToday) cls += 'border border-indigo-400 text-indigo-600 font-semibold cursor-pointer hover:bg-indigo-50';
                    else              cls += 'text-gray-700 cursor-pointer hover:bg-indigo-50';

                    const btn = document.createElement('div');
                    btn.className = cls;
                    btn.textContent = d;
                    if (!isPast) {
                        btn.addEventListener('click', () => {
                            selected = date;
                            dateInput.value = ymd;
                            dateInput.dispatchEvent(new Event('change'));
                            calLabel.textContent = `Selected: ${MONTHS[date.getMonth()]} ${d}, ${date.getFullYear()}`;
                            calLabel.classList.remove('hidden');
                            render();
                            refreshSlots();
                        });
                    }
                    calDays.appendChild(btn);
                }
            }

            btnPrev.addEventListener('click', () => {
                current = new Date(current.getFullYear(), current.getMonth()-1, 1);
                render();
            });
            btnNext.addEventListener('click', () => {
                current = new Date(current.getFullYear(), current.getMonth()+1, 1);
                render();
            });

            // restore pre-filled date label
            if (selected) {
                calLabel.textContent = `Selected: ${MONTHS[selected.getMonth()]} ${selected.getDate()}, ${selected.getFullYear()}`;
                calLabel.classList.remove('hidden');
                current = new Date(selected.getFullYear(), selected.getMonth(), 1);
            }

            render();
        })();

        // ── Table filters ───────────────────────────────────────────────────
        (function () {
            const filterBtns = document.querySelectorAll('.filter-btn');
            const cards      = document.querySelectorAll('.table-card');

            filterBtns.forEach(btn => {
                btn.addEventListener('click', () => {
                    filterBtns.forEach(b => b.classList.remove('active', 'bg-indigo-50', 'border-indigo-500', 'text-indigo-700'));
                    filterBtns.forEach(b => b.classList.add('border-gray-300', 'text-gray-600'));
                    btn.classList.add('active', 'bg-indigo-50', 'border-indigo-500', 'text-indigo-700');
                    btn.classList.remove('border-gray-300', 'text-gray-600');

                    const filter = btn.dataset.filter;
                    cards.forEach(card => {
                        card.style.display = (filter === 'all' || card.dataset.type === filter) ? '' : 'none';
                    });
                });
            });
        })();

        // ── Slot refresh ────────────────────────────────
        const tableCards  = document.querySelectorAll('.table-card');
        const slotSelect  = document.getElementById('time_slots_id');
        const dateInput   = document.getElementById('date');
        const bookedUrl   = '{{ route('api.booked-slots') }}';

        let selectedTableId = null;

        async function refreshSlots() {
            if (!selectedTableId || !dateInput.value) return;
            let booked = [];
            try {
                const res = await fetch(`${bookedUrl}?table_id=${encodeURIComponent(selectedTableId)}&date=${encodeURIComponent(dateInput.value)}`);
                booked = await res.json();
            } catch (_) {}
            const bookedSet = new Set(booked.map(String));
            Array.from(slotSelect.options).forEach(opt => {
                const taken = bookedSet.has(opt.value);
                opt.hidden   = taken;
                opt.disabled = taken;
                if (taken) opt.selected = false;
            });
        }

        tableCards.forEach(card => {
            card.addEventListener('click', () => {
                selectedTableId = card.dataset.tableId;
                refreshSlots();
            });
        });

        // trigger for pre-selected table (after validation errors)
        const preSelected = document.querySelector('.table-card input[type=radio]:checked');
        if (preSelected) {
            selectedTableId = preSelected.closest('.table-card').dataset.tableId;
            refreshSlots();
        }

        dateInput.addEventListener('change', refreshSlots);
    </script>
@endpush
