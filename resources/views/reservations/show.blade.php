<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reservation Details</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 text-gray-900 min-h-screen">
    <main class="max-w-3xl mx-auto px-4 py-10 sm:px-6 lg:px-8">
        <div class="mb-6 flex items-center justify-between gap-4">
            <h1 class="text-2xl sm:text-3xl font-semibold tracking-tight">Reservation #{{ $reservation->reservation_id }}</h1>
            <a href="{{ route('reservations.index') }}" class="inline-flex items-center rounded-lg border border-gray-300 px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100">Back to List</a>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white shadow-sm p-6 sm:p-8 space-y-6">
            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wider text-gray-500">Member</dt>
                    <dd class="mt-1 text-sm font-medium text-gray-900">{{ $reservation->member->first_name }} {{ $reservation->member->last_name }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wider text-gray-500">Email</dt>
                    <dd class="mt-1 text-sm text-gray-700">{{ $reservation->member->email }}</dd>
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
                                {{ $reservation->discount_tokens_used }} tokens (${!! number_format($reservation->discount_amount_saved, 2) }})
                            </span>
                        @else
                            <span class="text-gray-500">None</span>
                        @endif
                    </dd>
                </div>
                <div class="sm:col-span-2">
                    <dt class="text-xs font-semibold uppercase tracking-wider text-gray-500">Table</dt>
                    <dd class="mt-1 text-sm text-gray-700">{{ $reservation->reservedSlots->first()->table->name ?? 'N/A' }}</dd>
                </div>
            </dl>

            @if($reservation->member->loyalty_points > 0)
                <div class="border-t border-gray-200 pt-6">
                    <a href="{{ route('reservations.redeem', $reservation) }}" class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        Redeem Loyalty Tokens
                    </a>
                </div>
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
</body>
</html>
