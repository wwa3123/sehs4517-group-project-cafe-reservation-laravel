<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReservationRequest;
use App\Http\Requests\UpdateReservationRequest;
use App\Models\EventRegistration;
use App\Models\Member;
use App\Models\Reservation;
use App\Models\Table;
use App\Models\TimeSlot;
use App\Services\ReservationService;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function __construct(protected ReservationService $reservationService) {}

    public function index()
    {
        $user  = auth()->user();
        $query = Reservation::with('member', 'event', 'reservedSlots.table', 'reservedSlots.timeSlot', 'loyaltyTransactions');

        if ($user->role !== 'admin') {
            $joinedEventIds = EventRegistration::where('member_id', $user->member_id)
                ->where('payment_status', '!=', 'CANCELLED')
                ->pluck('event_id');

            $query->where(function ($q) use ($user, $joinedEventIds) {
                $q->where('member_id', $user->member_id);
                if ($joinedEventIds->isNotEmpty()) {
                    $q->orWhere(function ($q2) use ($joinedEventIds) {
                        $q2->whereIn('event_id', $joinedEventIds)
                           ->whereHas('member', fn ($m) => $m->where('role', 'system'));
                    });
                }
            });
        }

        $reservations = $query->paginate(15);
        return view('reservations.index', compact('reservations'));
    }

    public function create(Request $request)
    {
        $members     = Member::where('role', '!=', 'system')->orWhereNull('role')->get();
        $tables      = Table::all();
        $timeSlots   = TimeSlot::all();
        $prefillDate = $request->query('date');

        return view('reservations.create', compact('members', 'tables', 'timeSlots', 'prefillDate'));
    }

    public function store(StoreReservationRequest $request)
    {
        $reservation  = $this->reservationService->createReservation($request->validated());
        $thankYouData = $this->reservationService->buildThankYouData(
            $reservation,
            (int) $request->input('tokens_to_spend', 0)
        );

        return redirect()->route('reservation.thankyou')->with($thankYouData);
    }

    public function show(Reservation $reservation)
    {
        $user = auth()->user();

        if ($user->role !== 'admin' && $user->member_id !== $reservation->member_id) {
            $isJoinedEvent = $reservation->member?->role === 'system'
                && $reservation->event_id
                && EventRegistration::where('member_id', $user->member_id)
                    ->where('event_id', $reservation->event_id)
                    ->where('payment_status', '!=', 'CANCELLED')
                    ->exists();

            abort_unless($isJoinedEvent, 403);
        }

        $reservation->load('member', 'event', 'reservedSlots.table', 'reservedSlots.timeSlot', 'loyaltyTransactions');
        return view('reservations.show', compact('reservation'));
    }

    public function edit(Reservation $reservation)
    {
        if ($reservation->member?->role === 'system') {
            return redirect()->route('reservations.show', $reservation)
                ->withErrors(['error' => 'Event reservations cannot be edited here. Edit the event instead.']);
        }

        $reservation->load('reservedSlots');
        $currentTableId     = $reservation->reservedSlots->first()?->table_id;
        $currentTimeSlotIds = $reservation->reservedSlots->pluck('time_slots_id')->toArray();

        return view('reservations.edit', compact(
            'reservation',
            'currentTableId',
            'currentTimeSlotIds',
        ) + ['tables' => Table::all(), 'timeSlots' => TimeSlot::all()]);
    }

    public function update(UpdateReservationRequest $request, Reservation $reservation)
    {
        if ($reservation->member?->role === 'system') {
            abort(403, 'Event reservations cannot be edited directly.');
        }

        $this->reservationService->updateReservation($reservation, $request->validated());

        return redirect()->route('reservations.show', $reservation)->with('success', 'Reservation updated successfully.');
    }

    public function destroy(Reservation $reservation)
    {
        abort_unless(auth()->user()->role === 'admin', 403);

        if ($reservation->member?->role === 'system') {
            return redirect()->route('reservations.show', $reservation)
                ->withErrors(['error' => 'Event reservations cannot be deleted here. Delete the event instead.']);
        }

        $this->reservationService->deleteReservation($reservation);

        return redirect()->route('reservations.index')->with('success', 'Reservation deleted successfully.');
    }
}
