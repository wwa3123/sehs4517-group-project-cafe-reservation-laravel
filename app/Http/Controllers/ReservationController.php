<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReservationRequest;
use App\Http\Requests\UpdateReservationRequest;
use App\Models\Member;
use App\Models\Reservation;
use App\Models\Table;
use App\Models\TimeSlot;
use App\Services\ReservationService;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function __construct(protected ReservationService $reservationService) {}

    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Reservation::with('member', 'reservedSlots.table', 'reservedSlots.timeSlot', 'loyaltyTransactions');

        if ($user->role !== 'admin') {
            $query->where('member_id', $user->member_id);
        } elseif ($request->filled('status')) {
            $request->validate(['status' => ['string', 'in:'.implode(',', Reservation::statuses())]]);
            $query->where('status', $request->input('status'));
        }

        $reservations = $query->paginate(15)->withQueryString();

        return view('reservations.index', compact('reservations') + ['statuses' => Reservation::statuses()]);
    }

    public function create(Request $request)
    {
        $members = Member::where('role', '!=', 'system')->orWhereNull('role')->get();
        $tables = Table::all();
        $timeSlots = TimeSlot::all();
        $prefillDate = $request->query('date');

        return view('reservations.create', compact('members', 'tables', 'timeSlots', 'prefillDate'));
    }

    public function store(StoreReservationRequest $request)
    {
        $reservation = $this->reservationService->createReservation($request->validated());
        $thankYouData = $this->reservationService->buildThankYouData(
            $reservation,
            (int) $request->input('tokens_to_spend', 0)
        );

        return redirect()->route('reservation.thankyou')->with($thankYouData);
    }

    public function show(Reservation $reservation)
    {
        $user = auth()->user();

        abort_unless($user->role === 'admin' || $user->member_id === $reservation->member_id, 403);

        $reservation->load('member', 'reservedSlots.table', 'reservedSlots.timeSlot', 'loyaltyTransactions');

        return view('reservations.show', compact('reservation'));
    }

    public function edit(Reservation $reservation)
    {
        $reservation->load('reservedSlots');
        $currentTableId = $reservation->reservedSlots->first()?->table_id;
        $currentTimeSlotIds = $reservation->reservedSlots->pluck('time_slots_id')->toArray();

        return view('reservations.edit', compact(
            'reservation',
            'currentTableId',
            'currentTimeSlotIds',
        ) + ['tables' => Table::all(), 'timeSlots' => TimeSlot::all()]);
    }

    public function update(UpdateReservationRequest $request, Reservation $reservation)
    {
        $this->reservationService->updateReservation($reservation, $request->validated());

        return redirect()->route('reservations.show', $reservation)->with('success', 'Reservation updated successfully.');
    }

    public function destroy(Reservation $reservation)
    {
        abort_unless(auth()->user()->role === 'admin', 403);

        $this->reservationService->deleteReservation($reservation);

        return redirect()->route('reservations.index')->with('success', 'Reservation deleted successfully.');
    }

    public function updateStatus(Request $request, Reservation $reservation)
    {
        $request->validate([
            'status' => ['required', 'string', 'in:'.implode(',', Reservation::statuses())],
        ]);

        try {
            $this->reservationService->transitionStatus($reservation, $request->string('status')->toString());
        } catch (\DomainException $exception) {
            return back()->withErrors(['status' => $exception->getMessage()]);
        }

        return redirect()->route('reservations.show', $reservation)->with('success', 'Reservation status updated successfully.');
    }
}
