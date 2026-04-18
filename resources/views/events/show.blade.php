@extends('layouts.app')
@section('title', 'Event Details')
@section('content')
    <main class="max-w-5xl mx-auto px-4 py-10 sm:px-6 lg:px-8">
        <div class="mb-6 flex items-center justify-between gap-4">
            <h1 class="text-2xl sm:text-3xl font-semibold tracking-tight">{{ $event->event_name }}</h1>
            <div class="flex items-center gap-2">
                @if(auth()->user()?->role === 'admin')
                    <a href="{{ route('events.edit', $event) }}" class="inline-flex items-center rounded-lg border border-gray-300 px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100">Edit</a>
                    <form action="{{ route('events.destroy', $event) }}" method="POST" onsubmit="return confirm('Delete this event? This cannot be undone.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center rounded-lg border border-red-300 px-3 py-2 text-sm font-medium text-red-700 hover:bg-red-50">Delete</button>
                    </form>
                @endif
                <a href="{{ route('events.index') }}" class="inline-flex items-center rounded-lg border border-gray-300 px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100">Back to Events</a>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-6 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-700 text-sm">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 rounded-lg border border-red-200 bg-red-50 p-4 text-red-700">
                <ul class="list-disc pl-5 space-y-1 text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <section class="lg:col-span-2 rounded-xl border border-gray-200 bg-white shadow-sm p-6 space-y-6">
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wider text-gray-500">Date</dt>
                        <dd class="mt-1 text-sm text-gray-700">{{ \Carbon\Carbon::parse($event->event_date)->format('F j, Y h:i A') }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wider text-gray-500">Event Fee</dt>
                        <dd class="mt-1 text-sm text-gray-700">${{ number_format($event->event_fee / 100, 2) }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wider text-gray-500">Capacity</dt>
                        <dd class="mt-1 text-sm text-gray-700">{{ $event->max_participants }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wider text-gray-500">Registered</dt>
                        <dd class="mt-1 text-sm text-gray-700">{{ $registeredTickets }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wider text-gray-500">Available</dt>
                        <dd class="mt-1 text-sm text-gray-700">{{ $availableTickets }}</dd>
                    </div>
                </dl>

                <div>
                    <h2 class="text-lg font-semibold text-gray-900 mb-2">Description</h2>
                    <p class="text-sm text-gray-700">{{ $event->event_descriptions ?: 'No description provided.' }}</p>
                </div>
            </section>

            <aside class="rounded-xl border border-gray-200 bg-white shadow-sm p-6 space-y-4">
                <h2 class="text-lg font-semibold text-gray-900">Join This Event</h2>

                @auth
                @if($userRegistration && auth()->user()?->role !== 'admin')
                <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-700 text-sm space-y-1">
                    <p class="font-semibold">You have joined this event</p>
                    <p>Tickets: {{ $userRegistration->num_tickets }}</p>
                    <p>Status: {{ $userRegistration->payment_status }}</p>
                </div>
                @else
                <form action="{{ route('events.join', $event) }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label for="member_id" class="mb-1.5 block text-sm font-medium text-gray-700">Member</label>
                        @if(auth()->user()?->role === 'admin')
                        <select name="member_id" id="member_id" required class="block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="" disabled {{ old('member_id') ? '' : 'selected' }}>Select a member</option>
                            @foreach($members as $member)
                                <option value="{{ $member->member_id }}" {{ (string) old('member_id') === (string) $member->member_id ? 'selected' : '' }}>{{ $member->first_name }} {{ $member->last_name }}</option>
                            @endforeach
                        </select>
                        @else
                        <input type="text" disabled value="{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}" class="block w-full rounded-lg border border-gray-200 bg-gray-100 px-3 py-2.5 text-sm text-gray-600 cursor-not-allowed">
                        <input type="hidden" name="member_id" value="{{ auth()->user()->member_id }}">
                        @endif
                    </div>

                    <div>
                        <label for="num_tickets" class="mb-1.5 block text-sm font-medium text-gray-700">Number of Tickets</label>
                        <input type="number" name="num_tickets" id="num_tickets" value="{{ old('num_tickets', 1) }}" min="1" max="{{ max(1, $availableTickets) }}" required class="block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <button type="submit" class="inline-flex w-full items-center justify-center rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2" {{ $availableTickets < 1 ? 'disabled' : '' }}>
                        {{ $availableTickets < 1 ? 'Sold Out' : 'Join Event' }}
                    </button>
                </form>
                @endif
                @else
                <p class="text-sm text-gray-600">Please <a href="{{ route('login') }}" class="font-medium text-indigo-600 hover:underline">log in</a> to join this event.</p>
                @endauth
            </aside>
        </div>

        @if(auth()->user()?->role === 'admin')
        <section class="mt-6 rounded-xl border border-gray-200 bg-white shadow-sm p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-3">Registrations</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">Member</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">Tickets</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">Status</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($event->registrations as $registration)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-sm text-gray-700">{{ $registration->member?->first_name ?? 'Unknown' }} {{ $registration->member?->last_name ?? '' }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700">{{ $registration->num_tickets }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700">{{ $registration->payment_status }}</td>
                                <td class="px-4 py-3 text-sm">
                                    <form action="{{ route('events.registrations.destroy', [$event, $registration]) }}" method="POST" onsubmit="return confirm('Remove this registration?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center rounded-lg border border-red-300 px-3 py-1.5 text-xs font-medium text-red-700 hover:bg-red-50">Remove</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-8 text-center text-sm text-gray-500">No registrations yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
        @endif
    </main>
@endsection
