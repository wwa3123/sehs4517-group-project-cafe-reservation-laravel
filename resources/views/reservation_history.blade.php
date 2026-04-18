@extends('layouts.app')
@section('title', 'Reservation History')
@push('head')
<style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        .history-card {
            max-width: 1000px;
            width: 100%;
            background-color: var(--card-bg, #ffffff);
            border-radius: 40px;
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.05), 0 4px 8px rgba(0, 0, 0, 0.02);
            padding: 36px 32px 48px;
            border: 1px solid var(--border, #ddebe0);
            transition: all 0.25s ease;
            margin: 2rem auto;
        }

        .history-card .header {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            flex-wrap: wrap;
            margin-bottom: 28px;
            border-bottom: 2px solid #e9f5e3;
            padding-bottom: 16px;
        }

        .history-card .header h2 {
            font-size: 1.8rem;
            font-weight: 700;
            background: linear-gradient(135deg, var(--accent, #4c9f2f) 0%, #7ac74f 100%);
            background-clip: text;
            -webkit-background-clip: text;
            color: transparent;
            letter-spacing: -0.3px;
        }

        .history-card .table-wrapper { overflow-x: auto; margin-top: 20px; }

        .history-card table {
            width: 100%; border-collapse: collapse;
            border-radius: 24px; overflow: hidden;
        }

        .history-card th {
            background-color: var(--accent-tint, #e9f5e3);
            color: #2c5e1e;
            font-weight: 700;
            padding: 14px 12px;
            font-size: 0.95rem;
        }

        .history-card td {
            padding: 12px;
            border-bottom: 1px solid var(--border, #e2ecd9);
            color: var(--text-primary, #2a3a26);
            font-weight: 500;
        }

        .history-card tr:last-child td { border-bottom: none; }
        .history-card tr:hover td { background-color: var(--accent-tint, #f9fff7); }

        .history-tabs { display: flex; gap: 8px; margin-bottom: 24px; }
        .history-tab {
            padding: 8px 22px; border-radius: 40px; font-size: 0.9rem; font-weight: 600;
            border: 2px solid var(--border, #ddebe0); cursor: pointer;
            background: transparent; color: var(--text-muted, #6b7c68); transition: all 0.2s;
        }
        .history-tab.active {
            background: var(--accent, #4c9f2f); border-color: var(--accent, #4c9f2f);
            color: #fff;
        }
        .history-tab-panel { display: none; }
        .history-tab-panel.active { display: block; }

        .badge {
            display: inline-block; font-size: 0.75rem; font-weight: 700;
            padding: 2px 10px; border-radius: 20px;
        }
        .badge-upcoming { background: #e9f5e3; color: #2c6e1e; }
        .badge-past { background: #f3f4f6; color: #6b7c68; }

        .history-card .empty-message {
            text-align: center; padding: 48px 20px;
            color: var(--text-muted, #6b7c68);
            font-size: 1rem;
            background: var(--bg-page, #fafdf8);
            border-radius: 28px; margin-top: 20px;
        }

        .history-card { animation: histFadeUp 0.4s ease-out; }
        @keyframes histFadeUp {
            from { opacity: 0; transform: translateY(15px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        @media (max-width: 650px) {
            .history-card { padding: 24px 20px 36px; }
            .history-card .header h2 { font-size: 1.5rem; margin-bottom: 12px; }
            .history-card .header { flex-direction: column; gap: 12px; align-items: stretch; }
            .history-card th, .history-card td { padding: 10px 8px; font-size: 0.85rem; }
        }
</style>
@endpush
@section('content')
<div class="history-card">
    <div class="header">
        <h2>My Reservation History</h2>
    </div>

    <div class="history-tabs">
        <button class="history-tab active" data-tab="upcoming">Upcoming <span class="badge badge-upcoming">{{ $upcoming->total() }}</span></button>
        <button class="history-tab" data-tab="past">Past <span class="badge badge-past">{{ $past->total() }}</span></button>
        <button class="history-tab" data-tab="events">Events <span class="badge badge-upcoming">{{ $eventRegistrations->count() }}</span></button>
    </div>

    {{-- Upcoming --}}
    <div class="history-tab-panel active" id="tab-upcoming">
        <div class="table-wrapper">
            @if($upcoming->count() > 0)
                <table>
                    <thead>
                        <tr><th>#</th><th>Date</th><th>Table</th><th>Time Slot(s)</th><th>Guests</th><th></th></tr>
                    </thead>
                    <tbody>
                        @foreach($upcoming as $item)
                        <tr>
                            <td>{{ $item->reservation_id }}</td>
                            <td>{{ \Carbon\Carbon::parse($item->date)->format('M j, Y') }}</td>
                            <td>{{ optional($item->reservedSlots->first()?->table)->name ?? '—' }}</td>
                            <td>
                                @foreach($item->reservedSlots->sortBy('timeSlot.start_time') as $slot)
                                    <div style="white-space:nowrap;">{{ \Carbon\Carbon::parse($slot->timeSlot->start_time)->format('g:i A') }}–{{ \Carbon\Carbon::parse($slot->timeSlot->end_time)->format('g:i A') }}</div>
                                @endforeach
                            </td>
                            <td>{{ $item->num_guests }}</td>
                            <td><a href="{{ route('reservations.show', $item->reservation_id) }}" style="color:var(--accent,#4c9f2f);font-weight:600;white-space:nowrap;">View →</a></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="pagination-wrapper" style="margin-top:20px;text-align:center;">
                    {{ $upcoming->links() }}
                </div>
            @else
                <div class="empty-message">
                    No upcoming reservations. <a href="{{ route('reservations.index') }}" style="color:var(--accent,#4c9f2f);">Make a reservation</a>
                </div>
            @endif
        </div>
    </div>

    {{-- Past --}}
    <div class="history-tab-panel" id="tab-past">
        <div class="table-wrapper">
            @if($past->count() > 0)
                <table>
                    <thead>
                        <tr><th>#</th><th>Date</th><th>Table</th><th>Time Slot(s)</th><th>Guests</th><th></th></tr>
                    </thead>
                    <tbody>
                        @foreach($past as $item)
                        <tr>
                            <td>{{ $item->reservation_id }}</td>
                            <td>{{ \Carbon\Carbon::parse($item->date)->format('M j, Y') }}</td>
                            <td>{{ optional($item->reservedSlots->first()?->table)->name ?? '—' }}</td>
                            <td>
                                @foreach($item->reservedSlots->sortBy('timeSlot.start_time') as $slot)
                                    <div style="white-space:nowrap;">{{ \Carbon\Carbon::parse($slot->timeSlot->start_time)->format('g:i A') }}–{{ \Carbon\Carbon::parse($slot->timeSlot->end_time)->format('g:i A') }}</div>
                                @endforeach
                            </td>
                            <td>{{ $item->num_guests }}</td>
                            <td><a href="{{ route('reservations.show', $item->reservation_id) }}" style="color:var(--accent,#4c9f2f);font-weight:600;white-space:nowrap;">View →</a></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="pagination-wrapper" style="margin-top:20px;text-align:center;">
                    {{ $past->links() }}
                </div>
            @else
                <div class="empty-message">No past reservations.</div>
            @endif
        </div>
    </div>

    {{-- Events --}}
    <div class="history-tab-panel" id="tab-events">
        <div class="table-wrapper">
            @if($eventRegistrations->count() > 0)
                <table>
                    <thead>
                        <tr><th>#</th><th>Event</th><th>Date</th><th>Tickets</th><th>Fee</th><th>Status</th><th></th></tr>
                    </thead>
                    <tbody>
                        @foreach($eventRegistrations as $reg)
                        <tr>
                            <td>{{ $reg->registration_id }}</td>
                            <td>{{ $reg->event->event_name }}</td>
                            <td>{{ \Carbon\Carbon::parse($reg->event->event_date)->format('M j, Y') }}</td>
                            <td>{{ $reg->num_tickets }}</td>
                            <td>${{ number_format($reg->event->event_fee / 100, 2) }}</td>
                            <td>
                                @php
                                    $statusColors = ['PENDING' => '#b45309', 'CONFIRMED' => '#2c6e1e', 'CANCELLED' => '#991b1b'];
                                    $statusBg = ['PENDING' => '#fef3c7', 'CONFIRMED' => '#e9f5e3', 'CANCELLED' => '#fee2e2'];
                                    $color = $statusColors[$reg->payment_status] ?? '#374151';
                                    $bg = $statusBg[$reg->payment_status] ?? '#f3f4f6';
                                @endphp
                                <span class="badge" style="background:{{ $bg }};color:{{ $color }}">{{ $reg->payment_status }}</span>
                            </td>
                            <td><a href="{{ route('events.show', $reg->event) }}" style="color:var(--accent,#4c9f2f);font-weight:600;white-space:nowrap;">View →</a></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="empty-message">
                    No event registrations. <a href="{{ route('events.index') }}" style="color:var(--accent,#4c9f2f);">Browse events</a>
                </div>
            @endif
        </div>
    </div>
</div>
@push('scripts')
<script>
document.querySelectorAll('.history-tab').forEach(function(btn) {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.history-tab').forEach(function(b) { b.classList.remove('active'); });
        document.querySelectorAll('.history-tab-panel').forEach(function(p) { p.classList.remove('active'); });
        btn.classList.add('active');
        document.getElementById('tab-' + btn.dataset.tab).classList.add('active');
    });
});
</script>
@endpush
@endsection