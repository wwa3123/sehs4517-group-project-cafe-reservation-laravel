<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Member;
use App\Models\Table;
use App\Models\TimeSlot;
use Illuminate\Http\Request;
use App\Services\ReservationService;

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
            'member_id' => 'required|exists:members,member_id',
            'date' => 'required|date',
            'num_guests' => 'required|integer|min:1',
            'table_id' => 'required|exists:tables,table_id',
            'time_slots_id' => 'required|exists:time_slots,time_slots_id',
        ]);

        $this->reservationService->createReservation($request->all());

        return redirect()->route('reservations.index')->with('success', 'Reservation created successfully.');
    }

    public function show(Reservation $reservation)
    {
        $reservation->load('member', 'reservedSlots.table', 'reservedSlots.timeSlot');
        return view('reservations.show', compact('reservation'));
    }
}
