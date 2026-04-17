@extends('layouts.app')
@section('title', 'Home')
@push('head')
<style>
    .intro-wrapper {
        max-width: 820px;
        width: 100%;
        margin: 0 auto;
        padding: 48px 24px 64px;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 36px;
    }

    .intro-logo {
        width: 140px;
        height: 140px;
        object-fit: contain;
        border-radius: 50%;
        border: 3px solid var(--border, #ddebe0);
        box-shadow: 0 8px 24px rgba(76, 159, 47, 0.15);
    }

    .intro-brand {
        text-align: center;
    }

    .intro-brand h1 {
        font-size: 2.8rem;
        font-weight: 800;
        letter-spacing: -0.5px;
        background: linear-gradient(135deg, var(--accent, #4c9f2f) 0%, #7ac74f 100%);
        background-clip: text;
        -webkit-background-clip: text;
        color: transparent;
        line-height: 1.2;
    }

    .intro-tagline {
        font-size: 1.1rem;
        color: var(--text-muted, #6b7c68);
        font-style: italic;
        margin-top: 6px;
    }

    .intro-card {
        width: 100%;
        background-color: var(--card-bg, #ffffff);
        border-radius: 32px;
        border: 1px solid var(--border, #ddebe0);
        padding: 36px 40px;
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.04);
        animation: introFadeUp 0.5s ease-out;
    }

    .intro-card h3 {
        font-size: 1rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: var(--accent, #4c9f2f);
        margin-bottom: 16px;
    }

    .intro-card p {
        color: var(--text-secondary, #3a5a34);
        font-size: 0.975rem;
        line-height: 1.75;
        margin-bottom: 6px;
    }

    .intro-cta {
        display: flex;
        gap: 14px;
        flex-wrap: wrap;
        justify-content: center;
    }

    .intro-cta a {
        padding: 13px 30px;
        border-radius: 40px;
        font-size: 0.95rem;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.25s ease;
    }

    .btn-primary {
        background-color: var(--accent, #4c9f2f);
        color: #ffffff;
    }

    .btn-primary:hover {
        background-color: var(--accent-dark, #3b7e24);
        transform: translateY(-2px);
        box-shadow: 0 8px 18px rgba(76, 159, 47, 0.3);
    }

    .btn-outline {
        background-color: transparent;
        color: var(--accent, #4c9f2f);
        border: 2px solid var(--accent, #4c9f2f);
    }

    .btn-outline:hover {
        background-color: var(--accent-tint, #e9f5e3);
        transform: translateY(-2px);
    }

    @keyframes introFadeUp {
        from { opacity: 0; transform: translateY(16px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    @media (max-width: 600px) {
        .intro-brand h1 { font-size: 2rem; }
        .intro-card { padding: 28px 24px; }
    }
</style>
@endpush
@section('content')
<div class="intro-wrapper">

    {{-- Brand header --}}
    <div class="intro-brand">
        <img src="/cafe_logo.png" alt="Chit Chat Cafe Logo" class="intro-logo" onerror="this.style.display='none'">
        <h1>Chit Chat Cafe</h1>
        <p class="intro-tagline">Cozy Chit | Easy Chat</p>
    </div>

    {{-- Who we are --}}
    <div class="intro-card">
        <h3>Who we are</h3>
        <p>Welcome to Chit Chat Cafe, you can enjoy over 100 board &amp; card games in here.</p>
        <p>This cafe has 3 types of table: Standard, Gaming and VIP, providing all customers the most comfort, most motivating, and the most premium game experiences.</p>
        <p>In late July, we will have the "Strategy Game Tournament" — a competition for a maximum of 16 participants featuring strategic board games. A <strong>CHAMPION CUP</strong> will be awarded to the winner.</p>
        <p>Also, we have "Family Game Night" every Saturday. Come with your family and <strong>WIN the BIG PRIZE</strong>.</p>
        <p>Hope you have a wonderful and cozy game experience at Chit Chat Cafe.</p>
    </div>

    {{-- Did you know --}}
    <div class="intro-card">
        <h3>Did you know?</h3>
        <p>This cafe has been in business for over a hundred years. Back in 1922, the original owner Victoria Shek opened this cafe and created the game Catan, inviting everyone to play when they visited.</p>
        <p>This is the first boardgame cafe in the world. And here is a little secret — during World War II (1942–1945), this cafe once served as the headquarters of the Anti-Japanese Guerrillas, hailed as the <strong>"Sanctuary for the Allies"</strong>.</p>
    </div>

    {{-- Call to action --}}
    <div class="intro-cta">
        <a href="{{ route('reservations.index') }}" class="btn-primary">Make a Reservation</a>
        <a href="{{ route('events.index') }}" class="btn-outline">View Events</a>
    </div>

</div>
@endsection
