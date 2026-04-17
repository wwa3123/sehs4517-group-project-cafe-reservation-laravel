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

        <div class="table-wrapper">
            @if(isset($history) && count($history) > 0)
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Date</th>
                            <th>Guests</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($history as $item)
                        <tr>
                            <td>{{ $item->reservation_id }}</td>
                            <td>{{ $item->date }}</td>
                            <td>{{ $item->num_guests }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="pagination-wrapper" style="margin-top: 20px; text-align: center;">
                    {{ $history->links() }}
                </div>

            @else
                <div class="empty-message">
                    No reservations yet. <a href="/reserve" style="color:#4c9f2f;">Make your first reservation</a>
                </div>
            @endif
        </div>
    </div>
@endsection