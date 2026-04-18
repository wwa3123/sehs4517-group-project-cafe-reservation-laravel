<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Member;
use App\Models\TimeSlot;
use App\Services\EventService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function __construct(protected EventService $eventService) {}

    public function index()
    {
        $events = $this->eventService->listEvents();

        $joinedEventIds = auth()->check()
            ? $this->eventService->joinedEventIds(auth()->id())
            : collect();

        return view('events.index', compact('events', 'joinedEventIds'));
    }

    public function create()
    {
        $tables = \App\Models\Table::all();
        $timeSlots = TimeSlot::all();
        return view('events.create', compact('tables', 'timeSlots'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'event_name'          => ['required', 'string', 'max:255'],
            'event_descriptions'  => ['nullable', 'string', 'max:255'],
            'event_fee'           => ['required', 'integer', 'min:0', 'max:99999'],
            'max_participants'    => ['required', 'integer', 'min:1'],
            'event_date'          => ['required', 'date', 'after:now'],
            'num_guests'          => ['required', 'integer', 'min:1'],
            'table_id'            => ['required', 'array', 'min:1'],
            'table_id.*'          => ['exists:tables,table_id'],
            'time_slots_id'       => ['required', 'array', 'min:1'],
            'time_slots_id.*'     => [
                'distinct',
                'exists:time_slots,time_slots_id',
                function ($attribute, $value, $fail) use ($request) {
                    $eventDate = Carbon::parse($request->input('event_date'))->toDateString();
                    foreach ((array) $request->input('table_id') as $tableId) {
                        if ($this->eventService->isSlotBooked((int) $tableId, (int) $value, $eventDate)) {
                            $timeSlot  = TimeSlot::find($value);
                            $startTime = Carbon::parse($timeSlot->start_time)->format('h:i A');
                            $tableName = \App\Models\Table::find($tableId)?->name ?? $tableId;
                            $fail("Table '{$tableName}' is not available at {$startTime} on the event date.");
                            return;
                        }
                    }
                },
            ],
        ]);

        $event = $this->eventService->createEvent($request->all());

        return redirect()->route('events.show', $event)
            ->with('success', 'Event created and reservation slot reserved successfully.');
    }

    public function show(Event $event)
    {
        $event->load(['registrations.member']);

        $stats    = $this->eventService->ticketStats($event);
        $members  = Member::where('role', '!=', 'system')->orWhereNull('role')->orderBy('first_name')->get();

        $userRegistration = auth()->check()
            ? $event->registrations
                ->where('member_id', auth()->id())
                ->where('payment_status', '!=', 'CANCELLED')
                ->first()
            : null;

        $registeredTickets = $stats['registered'];
        $availableTickets  = $stats['available'];

        return view('events.show', compact('event', 'members', 'registeredTickets', 'availableTickets', 'userRegistration'));
    }

    public function join(Request $request, Event $event)
    {
        $rules = ['num_tickets' => ['required', 'integer', 'min:1']];

        if (auth()->user()->role === 'admin') {
            $rules['member_id'] = ['required', 'exists:members,member_id'];
        }

        $validated = $request->validate($rules);

        $memberId = auth()->user()->role === 'admin'
            ? (int) $validated['member_id']
            : (int) auth()->id();

        $result = $this->eventService->joinEvent($event, $memberId, (int) $validated['num_tickets']);

        if ($result !== true) {
            return back()->withErrors([$result['field'] => $result['message']])->withInput();
        }

        return redirect()->route('events.show', $event)->with('success', 'Successfully joined event.');
    }
}
