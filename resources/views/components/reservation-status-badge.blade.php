@props(['status'])

@php
    $labels = [
        \App\Models\Reservation::STATUS_CONFIRMED => 'Confirmed',
        \App\Models\Reservation::STATUS_CHECKED_IN => 'Checked in',
        \App\Models\Reservation::STATUS_COMPLETED => 'Completed',
        \App\Models\Reservation::STATUS_CANCELLED => 'Cancelled',
        \App\Models\Reservation::STATUS_NO_SHOW => 'No-show',
    ];
@endphp

<span {{ $attributes->class(['reservation-status', 'reservation-status--'.$status]) }}>
    {{ $labels[$status] ?? ucfirst(str_replace('_', ' ', $status)) }}
</span>
