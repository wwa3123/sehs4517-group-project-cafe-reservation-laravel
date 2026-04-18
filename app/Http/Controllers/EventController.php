<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\Member;
use App\Models\ReservedSlot;
use App\Models\Table;
use App\Models\TimeSlot;
use App\Services\ReservationService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EventController extends Controller
{
    protected ReservationService $reservationService;

    public function __construct(ReservationService $reservationService)
    {
        $this->reservationService = $reservationService;
    }

    public function index()
    {
        $events = Event::withSum([
            'registrations as registered_tickets' => function ($query) {
                $query->where('payment_status', '!=', 'CANCELLED');
            }
        ], 'num_tickets')
            ->orderBy('event_date')
            ->get()
            ->map(function (Event $event) {
                $registeredTickets = (int) ($event->registered_tickets ?? 0);

                $event->registered_tickets = $registeredTickets;
                $event->available_tickets = max(0, (int) $event->max_participants - $registeredTickets);

                return $event;
            });

        $joinedEventIds = auth()->check()
            ? EventRegistration::where('member_id', auth()->id())
                ->where('payment_status', '!=', 'CANCELLED')
                ->pluck('event_id')
                ->flip()
            : collect();

        return view('events.index', compact('events', 'joinedEventIds'));
    }

    public function create()
    {
        $tables = Table::all();
        $timeSlots = TimeSlot::all();
        return view('events.create', compact('tables', 'timeSlots'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'event_name' => ['required', 'string', 'max:255'],
            'event_descriptions' => ['nullable', 'string', 'max:255'],
            'event_fee' => ['required', 'integer', 'min:0'],
            'max_participants' => ['required', 'integer', 'min:1'],
            'event_date' => ['required', 'date', 'after_or_equal:today'],
            'num_guests' => ['required', 'integer', 'min:1'],
            'table_id' => ['required', 'array', 'min:1'],
            'table_id.*' => ['exists:tables,table_id'],
            'time_slots_id' => ['required', 'array', 'min:1'],
            'time_slots_id.*' => [
                'distinct',
                'exists:time_slots,time_slots_id',
                function ($attribute, $value, $fail) use ($request) {
                    $eventDate = Carbon::parse($request->input('event_date'))->toDateString();
                    foreach ((array) $request->input('table_id') as $tableId) {
                        $isBooked = ReservedSlot::where('table_id', $tableId)
                            ->where('time_slots_id', $value)
                            ->whereHas('reservation', fn ($q) => $q->whereDate('date', $eventDate))
                            ->exists();
                        if ($isBooked) {
                            $timeSlot = TimeSlot::find($value);
                            $startTime = Carbon::parse($timeSlot->start_time)->format('h:i A');
                            $tableName = \App\Models\Table::find($tableId)?->name ?? $tableId;
                            $fail("Table '{$tableName}' is not available at {$startTime} on the event date.");
                            return;
                        }
                    }
                },
            ],
        ]);

        $reservationService = $this->reservationService;

        $event = DB::transaction(function () use ($request, $reservationService) {
            $event = Event::create($request->only([
                'event_name', 'event_descriptions', 'event_fee', 'max_participants', 'event_date',
            ]));

            $systemMember = \App\Models\Member::create([
                'email'            => 'event-' . $event->event_id . '@system.local',
                'first_name'       => $event->event_name,
                'last_name'        => '(Event)',
                'password_hash'    => \Illuminate\Support\Facades\Hash::make(bin2hex(random_bytes(16))),
                'role'             => 'system',
                'subscribe_events' => false,
                'loyalty_points'   => 0,
            ]);

            foreach ((array) $request->input('table_id') as $tableId) {
                $reservationService->createReservation([
                    'member_id'     => $systemMember->member_id,
                    'date'          => Carbon::parse($event->event_date)->toDateString(),
                    'num_guests'    => $request->input('num_guests'),
                    'table_id'      => $tableId,
                    'time_slots_id' => $request->input('time_slots_id'),
                ]);
            }

            return $event;
        });

        return redirect()->route('events.show', $event)
            ->with('success', 'Event created and reservation slot reserved successfully.');
    }

    public function show(Event $event)
    {
        $event->load(['registrations.member']);

        $registeredTickets = (int) $event->registrations
            ->where('payment_status', '!=', 'CANCELLED')
            ->sum('num_tickets');

        $availableTickets = max(0, (int) $event->max_participants - $registeredTickets);
        $members = Member::where('role', '!=', 'system')->orWhereNull('role')->orderBy('first_name')->get();

        $userRegistration = auth()->check()
            ? $event->registrations
                ->where('member_id', auth()->id())
                ->where('payment_status', '!=', 'CANCELLED')
                ->first()
            : null;

        return view('events.show', compact('event', 'members', 'registeredTickets', 'availableTickets', 'userRegistration'));
    }

    public function join(Request $request, Event $event)
    {
        $validated = $request->validate([
            'member_id' => ['required', 'exists:members,member_id'],
            'num_tickets' => ['required', 'integer', 'min:1'],
        ]);

        $alreadyJoined = EventRegistration::where('event_id', $event->event_id)
            ->where('member_id', $validated['member_id'])
            ->where('payment_status', '!=', 'CANCELLED')
            ->exists();

        if ($alreadyJoined) {
            return back()->withErrors([
                'member_id' => 'This member has already joined this event.',
            ])->withInput();
        }

        return DB::transaction(function () use ($event, $validated) {
            $registeredTickets = (int) EventRegistration::query()
                ->where('event_id', $event->event_id)
                ->where('payment_status', '!=', 'CANCELLED')
                ->sum('num_tickets');

            $requested = (int) $validated['num_tickets'];
            $availableTickets = (int) $event->max_participants - $registeredTickets;

            if ($requested > $availableTickets) {
                return back()->withErrors([
                    'num_tickets' => 'Not enough available tickets for this event.',
                ])->withInput();
            }

            EventRegistration::create([
                'event_id' => $event->event_id,
                'member_id' => $validated['member_id'],
                'num_tickets' => $requested,
                'payment_status' => 'PENDING',
            ]);

            return redirect()->route('events.show', $event)->with('success', 'Successfully joined event.');
        });
    }
}
