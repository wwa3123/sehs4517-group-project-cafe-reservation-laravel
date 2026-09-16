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
            ->whereIn('status', [
                Reservation::STATUS_CONFIRMED,
                Reservation::STATUS_CHECKED_IN,
            ])
            ->orderBy('date')
            ->paginate(10, ['*'], 'upcoming_page');

        $past = Reservation::with('reservedSlots.table', 'reservedSlots.timeSlot')
            ->where('member_id', $memberId)
            ->where(function ($query) use ($today) {
                $query->where('date', '<', $today)
                    ->orWhereIn('status', [
                        Reservation::STATUS_COMPLETED,
                        Reservation::STATUS_CANCELLED,
                        Reservation::STATUS_NO_SHOW,
                    ]);
            })
            ->orderByDesc('date')
            ->paginate(10, ['*'], 'past_page');

        $eventRegistrations = EventRegistration::with('event')
            ->where('member_id', $memberId)
            ->whereHas('event')
            ->orderByDesc('created_at')
            ->paginate(10, ['*'], 'events_page');

        return view('reservation_history', compact('upcoming', 'past', 'eventRegistrations'));
    }
}