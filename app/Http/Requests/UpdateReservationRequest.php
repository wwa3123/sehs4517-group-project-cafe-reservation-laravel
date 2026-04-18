<?php

namespace App\Http\Requests;

use App\Models\ReservedSlot;
use App\Models\Table;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class UpdateReservationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $reservation = $this->route('reservation');

        return [
            'date'            => ['required', 'date'],
            'num_guests'      => [
                'required', 'integer', 'min:1',
                function ($attribute, $value, $fail) {
                    $table = Table::find($this->input('table_id'));
                    if ($table && $value > $table->capacity) {
                        $fail("Number of guests ({$value}) exceeds the table's maximum capacity of {$table->capacity}.");
                    }
                },
            ],
            'table_id'        => ['required', 'exists:tables,table_id'],
            'time_slots_id'   => ['required', 'array', 'min:1'],
            'time_slots_id.*' => [
                'distinct',
                'exists:time_slots,time_slots_id',
                function ($attribute, $value, $fail) use ($reservation) {
                    $date    = Carbon::parse($this->input('date'))->toDateString();
                    $tableId = (int) $this->input('table_id');
                    $booked  = ReservedSlot::where('table_id', $tableId)
                        ->where('time_slots_id', $value)
                        ->whereHas('reservation', fn ($q) => $q
                            ->whereDate('date', $date)
                            ->where('reservation_id', '!=', $reservation->reservation_id)
                        )->exists();
                    if ($booked) {
                        $timeSlot  = \App\Models\TimeSlot::find($value);
                        $startTime = Carbon::parse($timeSlot->start_time)->format('h:i A');
                        $fail("The selected table is not available at {$startTime}.");
                    }
                },
            ],
        ];
    }
}
