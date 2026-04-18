<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\Member;
use App\Models\Table;
use App\Models\TimeSlot;
use App\Services\EventService;
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
        return view('events.create', [
            'tables'    => Table::all(),
            'timeSlots' => TimeSlot::all(),
        ]);
    }

    public function store(StoreEventRequest $request)
    {
        $event = $this->eventService->createEvent($request->validated());

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

        return view('events.show', [
            'event'              => $event,
            'members'            => $members,
            'registeredTickets'  => $stats['registered'],
            'availableTickets'   => $stats['available'],
            'userRegistration'   => $userRegistration,
        ]);
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

    public function edit(Event $event)
    {
        return view('events.edit', [
            'event'     => $event,
            'tables'    => Table::all(),
            'timeSlots' => TimeSlot::all(),
        ] + $this->eventService->getEditData($event));
    }

    public function update(UpdateEventRequest $request, Event $event)
    {
        $this->eventService->updateEvent($event, $request->validated());

        return redirect()->route('events.show', $event)->with('success', 'Event updated successfully.');
    }

    public function destroy(Event $event)
    {
        $this->eventService->deleteEvent($event);

        return redirect()->route('events.index')->with('success', 'Event deleted successfully.');
    }

    public function removeRegistration(Event $event, EventRegistration $registration)
    {
        abort_if($registration->event_id !== $event->event_id, 404);

        $registration->delete();

        return redirect()->route('events.show', $event)->with('success', 'Registration removed.');
    }
}
