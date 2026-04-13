<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use App\Models\Reservation;

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