<?php

namespace App\Http\Requests;

use App\Models\Member;
use App\Models\Table;
use App\Services\ReservationService;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class StoreReservationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'member_id'       => [
                'required', 'exists:members,member_id',
                function ($attribute, $value, $fail) {
                    if (auth()->user()->role !== 'admin' && (int) $value !== auth()->id()) {
                        $fail('You can only make reservations for yourself.');
                    }
                },
            ],
            'event_id'        => ['nullable', 'exists:events,event_id'],
            'tokens_to_spend' => [
                'nullable', 'integer', 'min:0',
                function ($attribute, $value, $fail) {
                    if (!$value) return;
                    $member = Member::find((int) $this->input('member_id'));
                    if ($member && $value > $member->loyalty_points) {
                        $fail('The selected member does not have enough loyalty tokens.');
                    }
                },
            ],
            'date'            => ['required', 'date', 'after_or_equal:today'],
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
                function ($attribute, $value, $fail) {
                    $date    = Carbon::parse($this->input('date'))->toDateString();
                    $tableId = (int) $this->input('table_id');
                    if (app(ReservationService::class)->isSlotBooked($tableId, (int) $value, $date)) {
                        $timeSlot  = \App\Models\TimeSlot::find($value);
                        $startTime = Carbon::parse($timeSlot->start_time)->format('h:i A');
                        $fail("The selected table is not available at {$startTime}.");
                    }
                },
            ],
        ];
    }
}
