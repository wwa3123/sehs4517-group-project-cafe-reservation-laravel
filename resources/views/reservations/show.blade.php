@extends('layouts.app')
@section('title', 'Reservation Details')
@section('content')
    <main class="max-w-3xl mx-auto px-4 py-10 sm:px-6 lg:px-8">
        <div class="mb-6 flex items-center justify-between gap-4">
            <h1 class="text-2xl sm:text-3xl font-semibold tracking-tight">Reservation #{{ $reservation->reservation_id }}</h1>
            <div class="flex items-center gap-2">
                @if(auth()->user()?->role === 'admin')
                    @if($reservation->member?->role !== 'system')
                    <a href="{{ route('reservations.edit', $reservation) }}" class="inline-flex items-center rounded-lg border border-gray-300 px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100">Edit</a>
                    @endif
                    @if($reservation->member?->role !== 'system')
                    <form action="{{ route('reservations.destroy', $reservation) }}" method="POST" onsubmit="return confirm('Delete this reservation? This cannot be undone.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center rounded-lg border border-red-300 px-3 py-2 text-sm font-medium text-red-700 hover:bg-red-50">Delete</button>
                    </form>
                    @endif
                @endif
                <a href="{{ route('reservations.index') }}" class="inline-flex items-center rounded-lg border border-gray-300 px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100">Back to List</a>
            </div>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white shadow-sm p-6 sm:p-8 space-y-6">
            @if(session('success'))
                <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">{{ session('success') }}</div>
            @endif
            @error('status')
                <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">{{ $message }}</div>
            @enderror

            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wider text-gray-500">Member</dt>
                    <dd class="mt-1 text-sm font-medium text-gray-900">
                        {{ $reservation->member->first_name }} {{ $reservation->member->last_name }}
                    </dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wider text-gray-500">Email</dt>
                    <dd class="mt-1 text-sm text-gray-700">
                        {{ $reservation->member->email }}
                    </dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wider text-gray-500">Date</dt>
                    <dd class="mt-1 text-sm text-gray-700">{{ $reservation->date->format('F j, Y') }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wider text-gray-500">Number of Guests</dt>
                    <dd class="mt-1 text-sm text-gray-700">{{ $reservation->num_guests }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wider text-gray-500">Visit Status</dt>
                    <dd class="mt-1 text-sm text-gray-700">{{ ucfirst(str_replace('_', ' ', $reservation->status)) }}</dd>
                </div>
                @if($reservation->checked_in_at)
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wider text-gray-500">Checked In At</dt>
                        <dd class="mt-1 text-sm text-gray-700">{{ $reservation->checked_in_at->format('F j, Y g:i A') }}</dd>
                    </div>
                @endif
                @if($reservation->completed_at)
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wider text-gray-500">Completed At</dt>
                        <dd class="mt-1 text-sm text-gray-700">{{ $reservation->completed_at->format('F j, Y g:i A') }}</dd>
                    </div>
                @endif
                @if($reservation->cancelled_at)
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wider text-gray-500">Cancelled At</dt>
                        <dd class="mt-1 text-sm text-gray-700">{{ $reservation->cancelled_at->format('F j, Y g:i A') }}</dd>
                    </div>
                @endif
                @if($reservation->no_show_at)
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wider text-gray-500">Marked No-Show At</dt>
                        <dd class="mt-1 text-sm text-gray-700">{{ $reservation->no_show_at->format('F j, Y g:i A') }}</dd>
                    </div>
                @endif
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wider text-gray-500">Loyalty Tokens Earned</dt>
                    <dd class="mt-1 text-sm text-gray-700">{{ (int) optional($reservation->loyaltyTransactions->first())->points }} tokens</dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wider text-gray-500">Member Token Balance</dt>
                    <dd class="mt-1 text-sm text-gray-700">{{ $reservation->member->loyalty_points }} tokens</dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wider text-gray-500">Discount Redeemed</dt>
                    <dd class="mt-1 text-sm text-gray-700">
                        @if($reservation->discount_tokens_used > 0)
                            <span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-1 text-xs font-medium text-green-700">
                                {{ $reservation->discount_tokens_used }} tokens (${{ number_format($reservation->discount_amount_saved, 2) }})
                            </span>
                        @else
                            <span class="text-gray-500">None</span>
                        @endif
                    </dd>
                </div>
                <div class="sm:col-span-2">
                    <dt class="text-xs font-semibold uppercase tracking-wider text-gray-500">Table</dt>
                    <dd class="mt-1 text-sm text-gray-700">{{ $reservation->reservedSlots->first()?->table?->name ?? 'N/A' }}</dd>
                </div>
            </dl>

            @if(auth()->user()?->role === 'admin')
                @php
                    $availableActions = match ($reservation->status) {
                        \App\Models\Reservation::STATUS_CONFIRMED => [
                            'checked_in' => 'Check In',
                            'cancelled' => 'Cancel Reservation',
                            'no_show' => 'Mark No-Show',
                        ],
                        \App\Models\Reservation::STATUS_CHECKED_IN => ['completed' => 'Complete Visit'],
                        default => [],
                    };
                @endphp
                @if($availableActions)
                    <section class="border-t border-gray-200 pt-6">
                        <h2 class="text-lg font-semibold text-gray-900">Staff Actions</h2>
                        <div class="mt-3 flex flex-wrap gap-2">
                            @foreach($availableActions as $status => $label)
                                <form action="{{ route('reservations.status.update', $reservation) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="{{ $status }}">
                                    <button type="submit" class="inline-flex items-center rounded-lg border border-indigo-300 px-3 py-2 text-sm font-medium text-indigo-700 hover:bg-indigo-50">{{ $label }}</button>
                                </form>
                            @endforeach
                        </div>
                    </section>
                @endif
            @endif

            <section>
                <h2 class="text-lg font-semibold text-gray-900 mb-3">Reserved Time Slots</h2>
                <ul class="space-y-2">
                    @forelse($reservation->reservedSlots as $slot)
                        <li class="rounded-lg border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-700">
                            {{ \Carbon\Carbon::parse($slot->timeSlot->start_time)->format('h:i A') }} to {{ \Carbon\Carbon::parse($slot->timeSlot->end_time)->format('h:i A') }}
                        </li>
                    @empty
                        <li class="rounded-lg border border-dashed border-gray-300 bg-gray-50 px-4 py-3 text-sm text-gray-500">No time slots were reserved.</li>
                    @endforelse
                </ul>
            </section>

        </div>
    </main>
@endsection
