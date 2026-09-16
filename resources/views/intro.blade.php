@extends('layouts.app')
@section('title', 'Chit-Chat Cafe | Board games, good food, great company')
@push('head')
<style>
    .home-shell { max-width: 1100px; margin: 0 auto; padding: 4rem 1.5rem 5rem; }
    .home-hero { display: grid; grid-template-columns: 1.25fr .75fr; align-items: center; gap: 3rem; }
    .home-eyebrow { color: var(--accent); font-size: .8rem; font-weight: 800; letter-spacing: .12em; text-transform: uppercase; }
    .home-title { color: var(--text-primary); font-size: clamp(2.5rem, 6vw, 4.5rem); font-weight: 800; letter-spacing: -.05em; line-height: 1; margin: .75rem 0 1.25rem; }
    .home-copy { color: var(--text-secondary); font-size: 1.1rem; line-height: 1.7; max-width: 38rem; }
    .home-actions { display: flex; flex-wrap: wrap; gap: .75rem; margin-top: 2rem; }
    .home-action { border-radius: .75rem; font-weight: 700; padding: .85rem 1.1rem; text-decoration: none; transition: var(--transition); }
    .home-action-primary { background: var(--accent); color: #fff; }
    .home-action-primary:hover { background: var(--accent-dark); transform: translateY(-2px); }
    .home-action-secondary { border: 1px solid var(--border); color: var(--text-primary); }
    .home-action-secondary:hover { background: var(--accent-tint); border-color: var(--accent); }
    .home-mark { aspect-ratio: 1; background: var(--card-bg); border: 1px solid var(--border); border-radius: 2rem; box-shadow: var(--shadow); display: grid; place-items: center; padding: 2rem; }
    .home-mark-symbol { color: var(--accent); font-size: clamp(5rem, 15vw, 9rem); font-weight: 800; line-height: 1; }
    .home-mark-label { color: var(--text-muted); font-size: .8rem; font-weight: 700; letter-spacing: .12em; margin-top: 1rem; text-transform: uppercase; }
    .home-features { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; margin-top: 4rem; }
    .home-feature { background: var(--card-bg); border: 1px solid var(--border); border-radius: 1rem; padding: 1.5rem; }
    .home-feature h2 { color: var(--text-primary); font-size: 1.05rem; font-weight: 700; margin: .75rem 0 .4rem; }
    .home-feature p { color: var(--text-muted); font-size: .9rem; line-height: 1.6; }
    .home-feature-icon { color: var(--accent); font-size: 1.5rem; font-weight: 800; }
    @media (max-width: 700px) { .home-shell { padding-top: 2.5rem; } .home-hero { grid-template-columns: 1fr; gap: 2rem; } .home-mark { max-width: 18rem; } .home-features { grid-template-columns: 1fr; margin-top: 2.5rem; } }
</style>
@endpush
@section('content')
<main class="home-shell">
    <section class="home-hero" aria-labelledby="home-title">
        <div>
            <p class="home-eyebrow">Your local board-game cafe</p>
            <h1 id="home-title" class="home-title">Make time for play.</h1>
            <p class="home-copy">Chit-Chat Cafe is a welcoming fictional board-game cafe where groups can reserve a table, choose a game, share drinks and bites, and join community events.</p>
            <div class="home-actions">
                @auth
                    <a class="home-action home-action-primary" href="{{ route('reservations.create') }}">Reserve a table</a>
                @else
                    <a class="home-action home-action-primary" href="{{ route('register') }}">Create an account</a>
                @endauth
                <a class="home-action home-action-secondary" href="{{ route('events.index') }}">Browse events</a>
                <a class="home-action home-action-secondary" href="{{ route('menu') }}">View menu</a>
            </div>
        </div>
        <div class="home-mark">
            <div class="text-center">
                <div class="home-mark-symbol" aria-hidden="true">CC</div>
                <div class="home-mark-label">Chit-Chat Cafe</div>
            </div>
        </div>
    </section>

    <section class="home-features" aria-label="Cafe services">
        <article class="home-feature">
            <div class="home-feature-icon" aria-hidden="true">01</div>
            <h2>Reserve with confidence</h2>
            <p>Availability is protected by table, date, and time slot so groups avoid double bookings.</p>
        </article>
        <article class="home-feature">
            <div class="home-feature-icon" aria-hidden="true">02</div>
            <h2>Discover your next game</h2>
            <p>Choose a table that fits your group, then pair your session with food, drinks, and a game recommendation.</p>
        </article>
        <article class="home-feature">
            <div class="home-feature-icon" aria-hidden="true">03</div>
            <h2>Join the community</h2>
            <p>Explore hosted game nights and tournaments with capacity-aware event registration.</p>
        </article>
    </section>
</main>
@endsection
