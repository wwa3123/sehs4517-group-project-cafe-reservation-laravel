<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Redeem Loyalty Tokens</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 text-gray-900 min-h-screen">
    <main class="max-w-3xl mx-auto px-4 py-10 sm:px-6 lg:px-8">
        <div class="mb-6 flex items-center justify-between gap-4">
            <h1 class="text-2xl sm:text-3xl font-semibold tracking-tight">Redeem Loyalty Tokens</h1>
            <a href="{{ route('reservations.show', $reservation) }}" class="inline-flex items-center rounded-lg border border-gray-300 px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100">Back</a>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white shadow-sm p-6 sm:p-8 space-y-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pb-4 border-b border-gray-200">
                <div>
                    <p class="text-sm text-gray-600">Available Tokens</p>
                    <p class="text-lg font-semibold text-indigo-600">{{ $availableTokens }} tokens</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Conversion Rate</p>
                    <p class="text-lg font-semibold text-gray-900">2 tokens = $1</p>
                </div>
            </div>

            @if($discountTiers)
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Select Discount Tier</h2>
                    <form action="{{ route('reservations.applyDiscount', $reservation) }}" method="POST" class="space-y-3">
                        @csrf
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            @foreach($discountTiers as $tier)
                                <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                                    <input type="radio" name="tokens_to_spend" value="{{ $tier['tokens'] }}" class="h-4 w-4 text-indigo-600" required>
                                    <div class="ml-3">
                                        <p class="font-medium text-gray-900">{{ $tier['tokens'] }} tokens</p>
                                        <p class="text-sm text-gray-500">${{ number_format($tier['discount'], 2) }} discount</p>
                                    </div>
                                </label>
                            @endforeach
                        </div>

                        <div class="pt-4">
                            <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 w-full sm:w-auto">
                                Apply Discount
                            </button>
                        </div>
                    </form>
                </div>
            @else
                <div class="rounded-lg border border-yellow-200 bg-yellow-50 p-4 text-yellow-700 text-sm">
                    You don't have enough tokens to redeem any discount. Earn more tokens through additional reservations!
                </div>
            @endif

            <div class="border-t border-gray-200 pt-6">
                <h3 class="font-semibold text-gray-900 mb-3">Reservation Details</h3>
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                    <div>
                        <dt class="text-gray-600">Member</dt>
                        <dd class="font-medium text-gray-900">{{ $reservation->member->first_name }} {{ $reservation->member->last_name }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-600">Date</dt>
                        <dd class="font-medium text-gray-900">{{ $reservation->date->format('F j, Y') }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-600">Current Discount</dt>
                        <dd class="font-medium text-indigo-600">{{ $reservation->discount_tokens_used }} tokens (${!! number_format($reservation->discount_amount_saved, 2) }})</dd>
                    </div>
                </dl>
            </div>
        </div>
    </main>
</body>
</html>
