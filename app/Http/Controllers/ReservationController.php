<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Member;
use App\Models\Table;
use App\Models\TimeSlot;
use App\Models\ReservedSlot;
use Illuminate\Http\Request;
use App\Services\ReservationService;
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
        $reservations = Reservation::with('member', 'reservedSlots.table', 'reservedSlots.timeSlot')->get();
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
        $this->reservationService->createReservation($request->all());

        return redirect()->route('reservations.index')->with('success', 'Reservation created successfully for multiple time slots.');
    }

    public function show(Reservation $reservation)
    {
        $reservation->load('member', 'reservedSlots.table', 'reservedSlots.timeSlot');
        return view('reservations.show', compact('reservation'));
    }
}
