<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Game;
use App\Models\Member;
use App\Models\Reservation;
use App\Models\Table;
use App\Models\TimeSlot;
use App\Services\ReservationService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function __construct(protected ReservationService $reservationService) {}

    public function index()
    {
        $user  = auth()->user();
        $query = Reservation::with('member', 'event', 'reservedSlots.table', 'reservedSlots.timeSlot', 'loyaltyTransactions');

        if ($user->role !== 'admin') {
            $query->where('member_id', $user->member_id);
        }

        $reservations = $query->get();
        return view('reservations.index', compact('reservations'));
    }

    public function create(Request $request)
    {
        $members   = Member::where('role', '!=', 'system')->orWhereNull('role')->get();
        $events    = Event::orderBy('event_date')->get();
        $tables    = Table::all();
        $timeSlots = TimeSlot::all();
        $games     = Game::all();
        $prefillEventId = $request->query('event_id');
        $prefillDate    = $request->query('date');

        return view('reservations.create', compact('members', 'events', 'tables', 'timeSlots', 'games', 'prefillEventId', 'prefillDate'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'member_id'       => [
                'required', 'exists:members,member_id',
                function ($attribute, $value, $fail) {
                    if (auth()->user()->role !== 'admin' && (int) $value !== auth()->id()) {
                        $fail('You can only make reservations for yourself.');
                    }
                },
            ],
            'event_id'        => ['nullable', 'exists:events,event_id'],
            'tokens_to_spend' => ['nullable', 'integer', 'min:0'],
            'date'            => ['required', 'date', 'after_or_equal:today'],
            'num_guests'      => [
                'required', 'integer', 'min:1',
                function ($attribute, $value, $fail) use ($request) {
                    $table = Table::find($request->input('table_id'));
                    if ($table && $value > $table->capacity) {
                        $fail("Number of guests ({$value}) exceeds the table's maximum capacity of {$table->capacity}.");
                    }
                },
            ],
            'table_id'        => ['required', 'exists:tables,table_id'],
            'time_slots_id'   => ['required', 'array', 'min:1'],
            'time_slots_id.*' => [
                'distinct',
                'exists:time_slots,time_slots_id',
                function ($attribute, $value, $fail) use ($request) {
                    $date = Carbon::parse($request->input('date'))->toDateString();
                    if ($this->reservationService->isSlotBooked((int) $request->input('table_id'), (int) $value, $date)) {
                        $timeSlot  = TimeSlot::find($value);
                        $startTime = Carbon::parse($timeSlot->start_time)->format('h:i A');
                        $fail("The selected table is not available at {$startTime}.");
                    }
                },
            ],
        ]);

        $reservation = $this->reservationService->createReservation($request->all());
        $thankYouData = $this->reservationService->buildThankYouData(
            $reservation,
            (int) $request->input('tokens_to_spend', 0)
        );

        return redirect()->route('reservation.thankyou')->with($thankYouData);
    }

    public function show(Reservation $reservation)
    {
        $reservation->load('member', 'event', 'reservedSlots.table', 'reservedSlots.timeSlot', 'loyaltyTransactions');
        return view('reservations.show', compact('reservation'));
    }
}
