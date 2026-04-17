@extends('layouts.app')
@section('title', 'Live Occupancy')
@push('head')
<style>
    .occ-wrapper {
        max-width: 860px;
        margin: 0 auto;
        padding: 48px 24px 64px;
    }

    .occ-heading {
        font-size: 2rem;
        font-weight: 800;
        letter-spacing: -0.4px;
        background: linear-gradient(135deg, var(--accent, #4c9f2f) 0%, #7ac74f 100%);
        background-clip: text;
        -webkit-background-clip: text;
        color: transparent;
        margin-bottom: 4px;
    }

    .occ-subtitle {
        color: var(--text-muted, #6b7c68);
        font-size: 0.9rem;
        margin-bottom: 36px;
    }

    .occ-as-of {
        font-weight: 600;
        color: var(--accent, #4c9f2f);
    }

    /* Summary bar */
    .occ-summary {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
        margin-bottom: 32px;
    }

    .occ-stat {
        background: var(--card-bg, #fff);
        border: 1px solid var(--border, #ddebe0);
        border-radius: 20px;
        padding: 20px 16px;
        text-align: center;
    }

    .occ-stat-value {
        font-size: 2.4rem;
        font-weight: 800;
        line-height: 1;
        color: var(--text-primary, #1e2a1c);
    }

    .occ-stat-label {
        font-size: 0.78rem;
        text-transform: uppercase;
        letter-spacing: 0.07em;
        color: var(--text-muted, #6b7c68);
        margin-top: 6px;
        font-weight: 600;
    }

    /* Gauge / meter */
    .occ-meter-wrap {
        background: var(--card-bg, #fff);
        border: 1px solid var(--border, #ddebe0);
        border-radius: 20px;
        padding: 24px;
        margin-bottom: 32px;
    }

    .occ-meter-header {
        display: flex;
        justify-content: space-between;
        align-items: baseline;
        margin-bottom: 12px;
    }

    .occ-meter-label {
        font-size: 0.85rem;
        font-weight: 700;
        color: var(--text-secondary, #3a5a34);
        text-transform: uppercase;
        letter-spacing: 0.07em;
    }

    .occ-meter-pct {
        font-size: 1.4rem;
        font-weight: 800;
        color: var(--text-primary, #1e2a1c);
    }

    .occ-bar-bg {
        width: 100%;
        height: 18px;
        background: var(--accent-tint, #e9f5e3);
        border-radius: 99px;
        overflow: hidden;
    }

    .occ-bar-fill {
        height: 100%;
        border-radius: 99px;
        transition: width 0.6s ease, background-color 0.4s ease;
        background: var(--accent, #4c9f2f);
    }

    .occ-bar-fill.busy   { background: #f59e0b; }
    .occ-bar-fill.full   { background: #ef4444; }

    .occ-status-text {
        margin-top: 10px;
        font-size: 0.9rem;
        font-weight: 600;
    }

    .status-quiet  { color: var(--accent, #4c9f2f); }
    .status-busy   { color: #d97706; }
    .status-full   { color: #dc2626; }

    /* Table grid */
    .occ-tables-title {
        font-size: 0.8rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.09em;
        color: var(--text-muted, #6b7c68);
        margin-bottom: 14px;
    }

    .occ-tables-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(170px, 1fr));
        gap: 12px;
    }

    .occ-table-card {
        border-radius: 16px;
        padding: 16px 14px;
        border: 2px solid transparent;
        display: flex;
        flex-direction: column;
        gap: 4px;
        transition: all 0.3s ease;
    }

    .occ-table-card.available {
        background: var(--accent-tint, #f0f9ea);
        border-color: var(--accent, #4c9f2f);
    }

    .occ-table-card.occupied {
        background: #fef2f2;
        border-color: #fca5a5;
    }

    .occ-table-card-name {
        font-size: 0.9rem;
        font-weight: 700;
        color: var(--text-primary, #1e2a1c);
    }

    .occ-table-card-type {
        font-size: 0.78rem;
        color: var(--text-muted, #6b7c68);
    }

    .occ-table-dot {
        display: inline-block;
        width: 8px; height: 8px;
        border-radius: 50%;
        margin-right: 5px;
    }

    .dot-available { background: #22c55e; }
    .dot-occupied  { background: #ef4444; }

    .occ-table-status {
        font-size: 0.78rem;
        font-weight: 600;
        margin-top: 6px;
    }

    .occ-table-status.available { color: #16a34a; }
    .occ-table-status.occupied  { color: #dc2626; }

    .occ-refresh-note {
        text-align: center;
        font-size: 0.8rem;
        color: var(--text-muted, #6b7c68);
        margin-top: 28px;
    }

    #occ-spinner {
        display: inline-block;
        width: 10px; height: 10px;
        border: 2px solid var(--border, #ddebe0);
        border-top-color: var(--accent, #4c9f2f);
        border-radius: 50%;
        animation: spin 0.7s linear infinite;
        margin-right: 6px;
        vertical-align: middle;
    }

    @keyframes spin { to { transform: rotate(360deg); } }

    @media (max-width: 600px) {
        .occ-summary { grid-template-columns: 1fr 1fr 1fr; }
        .occ-stat-value { font-size: 1.8rem; }
        .occ-tables-grid { grid-template-columns: 1fr 1fr; }
    }
</style>
@endpush
@section('content')
<div class="occ-wrapper">

    <h1 class="occ-heading">Live Occupancy</h1>
    <p class="occ-subtitle">
        Real-time café busyness &mdash; updated every 30 seconds.
        As of <span id="occ-as-of" class="occ-as-of">—</span>
    </p>

    <!-- Summary stats -->
    <div class="occ-summary">
        <div class="occ-stat">
            <div class="occ-stat-value" id="occ-total">—</div>
            <div class="occ-stat-label">Total Tables</div>
        </div>
        <div class="occ-stat">
            <div class="occ-stat-value" id="occ-occupied" style="color:#ef4444">—</div>
            <div class="occ-stat-label">Occupied</div>
        </div>
        <div class="occ-stat">
            <div class="occ-stat-value" id="occ-available" style="color:#16a34a">—</div>
            <div class="occ-stat-label">Available</div>
        </div>
    </div>

    <!-- Occupancy meter -->
    <div class="occ-meter-wrap">
        <div class="occ-meter-header">
            <span class="occ-meter-label">Occupancy</span>
            <span class="occ-meter-pct" id="occ-pct">—</span>
        </div>
        <div class="occ-bar-bg">
            <div class="occ-bar-fill" id="occ-bar" style="width:0%"></div>
        </div>
        <div class="occ-status-text" id="occ-status-text"></div>
    </div>

    <!-- Per-table grid -->
    <div class="occ-tables-title">Table Status</div>
    <div class="occ-tables-grid" id="occ-tables-grid">
        <!-- populated by JS -->
    </div>

    <div class="occ-refresh-note">
        <span id="occ-spinner"></span>
        Auto-refreshes every 30 seconds
    </div>

</div>
@endsection
@push('scripts')
<script>
(function () {
    var apiUrl = '{{ route('occupancy.data') }}';

    function levelClass(pct) {
        if (pct >= 90) return 'full';
        if (pct >= 60) return 'busy';
        return '';
    }

    function statusLabel(pct) {
        if (pct >= 90) return { text: '🔴 Very Busy — limited availability', cls: 'status-full' };
        if (pct >= 60) return { text: '🟡 Fairly Busy — some tables free', cls: 'status-busy' };
        if (pct >= 30) return { text: '🟢 Moderate — plenty of space', cls: 'status-quiet' };
        return { text: '🟢 Quiet — come on in!', cls: 'status-quiet' };
    }

    function render(data) {
        document.getElementById('occ-as-of').textContent     = data.as_of;
        document.getElementById('occ-total').textContent     = data.total;
        document.getElementById('occ-occupied').textContent  = data.occupied;
        document.getElementById('occ-available').textContent = data.available;
        document.getElementById('occ-pct').textContent       = data.occupancy_pct + '%';

        var bar = document.getElementById('occ-bar');
        bar.style.width = data.occupancy_pct + '%';
        bar.className = 'occ-bar-fill ' + levelClass(data.occupancy_pct);

        var sl = statusLabel(data.occupancy_pct);
        var st = document.getElementById('occ-status-text');
        st.textContent  = sl.text;
        st.className    = 'occ-status-text ' + sl.cls;

        var grid = document.getElementById('occ-tables-grid');
        grid.innerHTML = '';
        data.tables.forEach(function (t) {
            var state = t.occupied ? 'occupied' : 'available';
            var card = document.createElement('div');
            card.className = 'occ-table-card ' + state;
            card.innerHTML =
                '<div class="occ-table-card-name">' + t.name + '</div>' +
                '<div class="occ-table-card-type">' + t.type + ' &bull; ' + t.capacity + ' seats</div>' +
                '<div class="occ-table-status ' + state + '">' +
                    '<span class="occ-table-dot dot-' + state + '"></span>' +
                    (t.occupied ? 'Occupied' : 'Available') +
                '</div>';
            grid.appendChild(card);
        });
    }

    function load() {
        fetch(apiUrl)
            .then(function (r) { return r.json(); })
            .then(render)
            .catch(function () {});
    }

    load();
    setInterval(load, 30000);
})();
</script>
@endpush
