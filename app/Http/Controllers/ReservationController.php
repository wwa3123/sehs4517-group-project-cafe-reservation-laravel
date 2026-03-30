<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ReservationService;
use App\Models\Table;
use App\Models\Reservation;
use Illuminate\Support\Facades\Auth;
use Exception;

class ReservationController extends Controller
{
    protected $reservationService;

    // Use dependency injection to get an instance of our service
    public function __construct(ReservationService $reservationService)
    {
        $this->reservationService = $reservationService;
    }

    /**
     * Show the form for creating a new reservation.
     */
    public function create()
    {
        return view('reservations.create');
    }

    /**
     * Store a newly created reservation in storage.
     */
    public function store(Request $request)
    {
        // 1. Validate the user's input
        $validated = $request->validate([
            'table_id' => 'required|exists:tables,table_id', // Corrected validation
            'start_time' => 'required|date|after_or_equal:now',
            'end_time' => 'required|date|after:start_time',
            'num_guests' => 'required|integer|min:1',
            'notes' => 'nullable|string|max:1000',
        ]);

        try {
            // 2. Call the service to do the heavy lifting
            $reservation = $this->reservationService->createReservation(
                Auth::id(), // Get the logged-in member's ID
                Table::class, // The type of model being reserved
                $validated['table_id'],
                $validated['start_time'],
                $validated['end_time'],
                $validated['num_guests'],
                $validated['notes']
            );

            // 3. If successful, redirect with a success message
            return redirect()->route('reservations.show', $reservation)
                ->with('success', 'Your reservation has been confirmed!');
        } catch (Exception $e) {
            // 4. If the service threw an exception (e.g., slot not available),
            // redirect back with the error message.
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified reservation.
     */
    public function show(Reservation $reservation)
    {
        return view('reservations.show', compact('reservation'));
    }

    /**
     * Display a listing of the user's reservations.
     */
    public function index()
    {
        $reservations = Reservation::where('member_id', Auth::id())
            ->orderBy('reservation_start_time', 'desc') // Order by the new column
            ->get();
        return response()->json($reservations);
    }

    public function getAllReservation()
    {
        $reservations = Reservation::all();
        return response()->json($reservations);
    }
}
