<?php

namespace App\Http\Controllers;

use App\Models\EventRegistration;
use App\Models\Reservation;
use Illuminate\Support\Facades\Auth;

class HistoryController extends Controller
{
    public function index()
    {
        $memberId = Auth::id();
        $today = now()->toDateString();

        $upcoming = Reservation::with('reservedSlots.table', 'reservedSlots.timeSlot')
            ->where('member_id', $memberId)
            ->where('date', '>=', $today)
            ->orderBy('date')
            ->paginate(10, ['*'], 'upcoming_page');

        $past = Reservation::with('reservedSlots.table', 'reservedSlots.timeSlot')
            ->where('member_id', $memberId)
            ->where('date', '<', $today)
            ->orderByDesc('date')
            ->paginate(10, ['*'], 'past_page');

        $eventRegistrations = EventRegistration::with('event')
            ->where('member_id', $memberId)
            ->whereHas('event')
            ->orderByDesc('created_at')
            ->get();

        return view('reservation_history', compact('upcoming', 'past', 'eventRegistrations'));
    }
}