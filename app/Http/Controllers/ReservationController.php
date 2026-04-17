<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Member;
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
        $reservations = Reservation::with('member', 'reservedSlots.table', 'reservedSlots.timeSlot', 'loyaltyTransactions')->get();
        return view('reservations.index', compact('reservations'));
    }

    public function create()
    {
        $members = Member::all();
        $tables = Table::all();
        $timeSlots = TimeSlot::all();
        return view('reservations.create', compact('members', 'tables', 'timeSlots'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'member_id' => ['required', 'exists:members,member_id'],
            'date' => ['required', 'date', 'after_or_equal:today'],
            'num_guests' => ['required', 'integer', 'min:1'],
            'table_id' => ['required', 'exists:tables,table_id'],

            // --- VALIDATION FOR MULTIPLE SLOTS ---
            'time_slots_id' => ['required', 'array', 'min:1'],

            // Use the '*' to apply this rule to each item in the time_slots_id array
            'time_slots_id.*' => [
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
        
        // If validation passes, proceed to the service
        $reservation = $this->reservationService->createReservation($request->all());
        $earnedTokens = (int) optional($reservation->loyaltyTransactions->first())->points;

        return redirect()->route('reservations.index')->with(
            'success',
            "Reservation created successfully. Earned {$earnedTokens} loyalty tokens. Current balance: {$reservation->member->loyalty_points}."
        );
    }

    public function show(Reservation $reservation)
    {
        $reservation->load('member', 'reservedSlots.table', 'reservedSlots.timeSlot', 'loyaltyTransactions');
        return view('reservations.show', compact('reservation'));
    }

    public function redeem($reservation)
    {
        $reservation = Reservation::with('member')->findOrFail($reservation);
        $member = $reservation->member;
        $availableTokens = $member->loyalty_points - $reservation->discount_tokens_used;
        $discountTiers = LoyaltyRedemptionService::getDiscountTiers($availableTokens);

        return view('reservations.redeem', compact('reservation', 'discountTiers', 'availableTokens'));
    }

    public function applyDiscount(Request $request, $reservation)
    {
        $reservation = Reservation::with('member')->findOrFail($reservation);
        $request->validate([
            'tokens_to_spend' => ['required', 'integer', 'min:1', 'max:' . $reservation->member->loyalty_points],
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
