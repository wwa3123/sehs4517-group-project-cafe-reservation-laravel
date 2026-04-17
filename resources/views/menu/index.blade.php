@extends('layouts.app')
@section('title', 'Menu')
@push('head')
<style>
    .menu-wrapper {
        max-width: 900px;
        margin: 0 auto;
        padding: 48px 24px 64px;
    }

    .menu-heading {
        font-size: 2.2rem;
        font-weight: 800;
        letter-spacing: -0.4px;
        background: linear-gradient(135deg, var(--accent, #4c9f2f) 0%, #7ac74f 100%);
        background-clip: text;
        -webkit-background-clip: text;
        color: transparent;
        margin-bottom: 8px;
    }

    .menu-subheading {
        color: var(--text-muted, #6b7c68);
        font-size: 0.95rem;
        margin-bottom: 40px;
    }

    .menu-category-title {
        font-size: 0.8rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        color: var(--accent, #4c9f2f);
        margin-bottom: 14px;
        padding-bottom: 8px;
        border-bottom: 2px solid var(--border, #e9f5e3);
    }

    .menu-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
        gap: 16px;
        margin-bottom: 40px;
    }

    .menu-card {
        background-color: var(--card-bg, #ffffff);
        border: 1px solid var(--border, #ddebe0);
        border-radius: 20px;
        overflow: hidden;
        transition: box-shadow 0.2s, transform 0.2s;
    }

    .menu-card:hover {
        box-shadow: 0 8px 24px rgba(76, 159, 47, 0.12);
        transform: translateY(-2px);
    }

    .menu-card-img {
        width: 100%;
        height: 150px;
        object-fit: cover;
    }

    .menu-card-img-placeholder {
        width: 100%;
        height: 150px;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: var(--accent-tint, #e9f5e3);
        font-size: 2.5rem;
    }

    .menu-card-body {
        padding: 16px;
    }

    .menu-card-name {
        font-size: 0.975rem;
        font-weight: 700;
        color: var(--text-primary, #1e2a1c);
        margin-bottom: 4px;
    }

    .menu-card-desc {
        font-size: 0.85rem;
        color: var(--text-muted, #6b7c68);
        line-height: 1.5;
    }

    .menu-empty {
        text-align: center;
        padding: 80px 20px;
        color: var(--text-muted, #6b7c68);
    }

    @media (max-width: 600px) {
        .menu-heading { font-size: 1.7rem; }
        .menu-grid { grid-template-columns: 1fr 1fr; gap: 12px; }
    }
</style>
@endpush
@section('content')
<div class="menu-wrapper">

    <h1 class="menu-heading">Our Menu</h1>
    <p class="menu-subheading">Fresh drinks &amp; bites to enjoy alongside your game.</p>

    @if($items->isEmpty())
        <div class="menu-empty">No menu items available at the moment.</div>
    @else
        @foreach($items as $category => $categoryItems)
        <div>
            <h2 class="menu-category-title">{{ $category }}</h2>
            <div class="menu-grid">
                @foreach($categoryItems as $item)
                <div class="menu-card">
                    @if($item->photo_url)
                        <img src="{{ $item->photo_url }}" alt="{{ $item->item_name }}" class="menu-card-img">
                    @else
                        <div class="menu-card-img-placeholder">🍽️</div>
                    @endif
                    <div class="menu-card-body">
                        <div class="menu-card-name">{{ $item->item_name }}</div>
                        @if($item->description)
                            <div class="menu-card-desc">{{ $item->description }}</div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endforeach
    @endif

</div>
@endsection
