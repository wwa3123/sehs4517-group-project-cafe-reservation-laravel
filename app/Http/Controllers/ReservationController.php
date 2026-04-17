<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Member;
use App\Models\Event;
use App\Models\Game;
use App\Models\Table;
use App\Models\TimeSlot;
use App\Models\ReservedSlot;
use Illuminate\Http\Request;
use App\Services\ReservationService;
use App\Services\LoyaltyRedemptionService;
use Carbon\Carbon;

class ReservationController extends Controller
{
    protected $reservationService;

    public function __construct(ReservationService $reservationService)
    {
        $this->reservationService = $reservationService;
    }

    public function index()
    {
        $user = auth()->user();

        $query = Reservation::with('member', 'event', 'reservedSlots.table', 'reservedSlots.timeSlot', 'loyaltyTransactions');

        if ($user->role !== 'admin') {
            $query->where('member_id', $user->member_id);
        }

        $reservations = $query->get();
        return view('reservations.index', compact('reservations'));
    }

    public function create(Request $request)
    {
        $members = Member::where('role', '!=', 'system')
            ->orWhereNull('role')
            ->get();
        $events = Event::orderBy('event_date')->get();
        $tables = Table::all();
        $timeSlots = TimeSlot::all();
        $games = Game::all();
        $prefillEventId = $request->query('event_id');
        $prefillDate = $request->query('date');

        return view('reservations.create', compact('members', 'events', 'tables', 'timeSlots', 'games', 'prefillEventId', 'prefillDate'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'member_id' => ['required', 'exists:members,member_id'],
            'date' => ['required', 'date', 'after_or_equal:today'],
            'num_guests' => [
                'required', 'integer', 'min:1',
                function ($attribute, $value, $fail) use ($request) {
                    $table = Table::find($request->input('table_id'));
                    if ($table && $value > $table->capacity) {
                        $fail("Number of guests ({$value}) exceeds the table's maximum capacity of {$table->capacity}.");
                    }
                },
            ],
            'table_id' => ['required', 'exists:tables,table_id'],
            'time_slots_id' => ['required', 'array', 'min:1'],
            'time_slots_id.*' => [
                'distinct',
                'exists:time_slots,time_slots_id',
                function ($attribute, $value, $fail) use ($request) {
                    $isBooked = ReservedSlot::where('table_id', $request->input('table_id'))
                        ->where('time_slots_id', $value)
                        ->whereHas('reservation', function ($query) use ($request) {
                            $query->whereDate('date', Carbon::parse($request->input('date'))->toDateString());
                        })
                        ->exists();

                    if ($isBooked) {
                        $timeSlot = TimeSlot::find($value);
                        $startTime = Carbon::parse($timeSlot->start_time)->format('h:i A');
                        $fail("The selected table is not available at {$startTime}.");
                    }
                },
            ],
        ]);

        // If validation passes, proceed to the service
        $reservation = $this->reservationService->createReservation($request->all());
        $earnedTokens = (int) optional($reservation->loyaltyTransactions->first())->points;

        // Apply loyalty token redemption if requested
        $tokensToSpend = (int) $request->input('tokens_to_spend', 0);
        $discountApplied = 0;
        if ($tokensToSpend > 0 && $reservation->member) {
            $reservation->load('member');
            $applied = LoyaltyRedemptionService::applyDiscount($reservation, $reservation->member, $tokensToSpend);
            if ($applied) {
                $discountApplied = LoyaltyRedemptionService::calculateDiscount($tokensToSpend);
            }
        }

        $reservation->member->refresh();

        $member     = $reservation->member;
        $timeSlots  = $reservation->reservedSlots->load('timeSlot');
        $firstSlot  = optional($timeSlots->first())->timeSlot;
        $table      = optional($reservation->reservedSlots->first())->table ?? $reservation->table;

        $timeLabel = $firstSlot
            ? Carbon::parse($firstSlot->start_time)->format('g:i A') . ' – ' . Carbon::parse($firstSlot->end_time)->format('g:i A')
            : 'N/A';

        $popularGames = \App\Models\Game::inRandomOrder()->limit(3)->get('title')->pluck('title')->toArray();
        if (empty($popularGames)) {
            $popularGames = ['Catan', 'Ticket to Ride', 'Codenames'];
        }

        return redirect()->route('reservation.thankyou')->with([
            'email'          => $member->email,
            'date'           => Carbon::parse($reservation->date)->format('F j, Y'),
            'timeSlot'       => $timeLabel,
            'table'          => optional($table)->table_name ?? 'Table',
            'earnedTokens'   => $earnedTokens,
            'discountApplied'=> $discountApplied,
            'gameSuggestions'=> $popularGames,
        ]);
    }

    public function show(Reservation $reservation)
    {
        $reservation->load('member', 'event', 'reservedSlots.table', 'reservedSlots.timeSlot', 'loyaltyTransactions');
        return view('reservations.show', compact('reservation'));
    }

}
