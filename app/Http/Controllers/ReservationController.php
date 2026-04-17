<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Member;
use App\Models\Event;
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
        $prefillEventId = $request->query('event_id');
        $prefillDate = $request->query('date');

        return view('reservations.create', compact('members', 'events', 'tables', 'timeSlots', 'prefillEventId', 'prefillDate'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'member_id' => ['nullable', 'required_without:event_id', 'exists:members,member_id'],
            'event_id' => ['nullable', 'exists:events,event_id'],
            'date' => ['required', 'date', 'after_or_equal:today'],
            'num_guests' => ['required', 'integer', 'min:1'],
            'table_id' => ['required', 'exists:tables,table_id'],

            // --- VALIDATION FOR MULTIPLE SLOTS ---
            'time_slots_id' => ['required', 'array', 'min:1'],

            // Use the '*' to apply this rule to each item in the time_slots_id array
            'time_slots_id.*' => [
                'distinct',
                'exists:time_slots,time_slots_id',

                // Custom Closure Rule for availability
                function ($attribute, $value, $fail) use ($request) {
                    // $attribute is 'time_slots_id.0', 'time_slots_id.1', etc.
                    // $value is the actual time_slot_id
                    
                    $isBooked = ReservedSlot::where('table_id', $request->input('table_id'))
                        ->where('time_slots_id', $value)
                        ->whereHas('reservation', function ($query) use ($request) {
                            $query->whereDate('date', Carbon::parse($request->input('date'))->toDateString());
                        })
                        ->exists();

                    if ($isBooked) {
                        // Find the time slot to make the error message more user-friendly
                        $timeSlot = TimeSlot::find($value);
                        $startTime = Carbon::parse($timeSlot->start_time)->format('h:i A');
                        $fail("The selected table is not available at {$startTime}.");
                    }
                },
            ],
        ]);

        if ($request->filled('event_id')) {
            $event = Event::findOrFail($request->input('event_id'));
            $reservationDate = Carbon::parse($request->input('date'))->toDateString();
            $eventDate = Carbon::parse($event->event_date)->toDateString();

            if ($reservationDate !== $eventDate) {
                return back()->withErrors([
                    'date' => 'Reservation date must match the selected event date.',
                ])->withInput();
            }

            if ((int) $request->input('num_guests') > (int) $event->max_participants) {
                return back()->withErrors([
                    'num_guests' => 'Number of guests cannot exceed event maximum participants.',
                ])->withInput();
            }
        }
        
        // If validation passes, proceed to the service
        $reservation = $this->reservationService->createReservation($request->all());
        $earnedTokens = (int) optional($reservation->loyaltyTransactions->first())->points;

        $successMessage = "Reservation created successfully.";

        if (!$reservation->event) {
            $successMessage .= " Earned {$earnedTokens} loyalty tokens. Current balance: {$reservation->member->loyalty_points}.";
        }

        if ($reservation->event) {
            $successMessage .= " Linked event: {$reservation->event->event_name}. Reservation owner is set to system dummy member.";
        }

        return redirect()->route('reservations.index')->with('success', $successMessage);
    }

    public function show(Reservation $reservation)
    {
        $reservation->load('member', 'event', 'reservedSlots.table', 'reservedSlots.timeSlot', 'loyaltyTransactions');
        return view('reservations.show', compact('reservation'));
    }

    public function redeem($reservation)
    {
        $reservation = Reservation::with('member')->findOrFail($reservation);
        $member = $reservation->member;
        $availableTokens = $member->loyalty_points;
        $discountTiers = LoyaltyRedemptionService::getDiscountTiers($availableTokens);

        return view('reservations.redeem', compact('reservation', 'discountTiers', 'availableTokens'));
    }

    public function applyDiscount(Request $request, $reservation)
    {
        $reservation = Reservation::with('member')->findOrFail($reservation);
        $availableTokens = max(0, $reservation->member->loyalty_points - $reservation->discount_tokens_used);

        if ($reservation->discount_tokens_used > 0 || $reservation->discount_amount_saved > 0) {
            return back()->withErrors('A loyalty discount has already been applied to this reservation.');
        }

        if ($availableTokens < 1) {
            return back()->withErrors('No loyalty tokens are available to redeem for this reservation.');
        }

        $request->validate([
            'tokens_to_spend' => ['required', 'integer', 'min:1', 'max:' . $availableTokens],
        ]);

        $tokens = $request->input('tokens_to_spend');
        $success = LoyaltyRedemptionService::applyDiscount(
            $reservation,
            $reservation->member,
            $tokens
        );

        if (!$success) {
            return back()->withErrors('Unable to apply discount.');
        }

        $discountAmount = LoyaltyRedemptionService::calculateDiscount($tokens);
        return redirect()->route('reservations.show', $reservation)->with(
            'success',
            "Applied {$tokens} loyalty tokens for \${$discountAmount} discount!"
        );
    }
}
