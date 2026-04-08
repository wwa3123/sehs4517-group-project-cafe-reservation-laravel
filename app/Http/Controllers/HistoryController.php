<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class HistoryController extends Controller
{
    public function index()
    {
        if (!Session::has('member_id')) {
            return redirect()->route('login');
        }

        $memberId = Session::get('member_id');

        $history = DB::table('reservations')
            ->where('member_id', $memberId)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('reservation_history', compact('history'));
    }
}
