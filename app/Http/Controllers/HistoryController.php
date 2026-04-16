<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Support\Facades\Auth;

class HistoryController extends Controller
{
    public function index()
    {
        $memberId = Session::get('member.id');

        $history = Reservation::where('member_id', $memberId)
            ->latest()
            ->paginate(10);

        return view('reservation_history', compact('history'));
    }
}