<?php

namespace App\Http\Requests;

use App\Models\Member;
use App\Models\Reservation;
use App\Models\ReservedSlot;
use App\Models\Table;
use App\Models\TimeSlot;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class UpdateEventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $event = $this->route('event');

        return [
            'event_name'         => ['required', 'string', 'max:255'],
            'event_descriptions' => ['nullable', 'string', 'max:255'],
            'event_fee'          => ['required', 'integer', 'min:0', 'max:99999'],
            'max_participants'   => ['required', 'integer', 'min:1'],
            'event_date'         => ['required', 'date'],
            'num_guests'         => ['required', 'integer', 'min:1'],
            'table_id'           => ['required', 'array', 'min:1'],
            'table_id.*'         => ['exists:tables,table_id'],
            'time_slots_id'      => ['required', 'array', 'min:1'],
            'time_slots_id.*'    => [
                'distinct',
                'exists:time_slots,time_slots_id',
                function ($attribute, $value, $fail) use ($event) {
                    $eventDate    = Carbon::parse($this->input('event_date'))->toDateString();
                    $systemMember = Member::where('email', 'event-' . $event->event_id . '@system.local')->first();
                    $excludeIds   = $systemMember
                        ? Reservation::where('member_id', $systemMember->member_id)->pluck('reservation_id')->toArray()
                        : [];

                    foreach ((array) $this->input('table_id') as $tableId) {
                        $booked = ReservedSlot::where('table_id', $tableId)
                            ->where('time_slots_id', $value)
                            ->whereHas('reservation', fn ($q) => $q
                                ->whereDate('date', $eventDate)
                                ->whereNotIn('reservation_id', $excludeIds)
                            )->exists();

                        if ($booked) {
                            $timeSlot  = TimeSlot::find($value);
                            $startTime = Carbon::parse($timeSlot->start_time)->format('h:i A');
                            $tableName = Table::find($tableId)?->name ?? $tableId;
                            $fail("Table '{$tableName}' is not available at {$startTime} on the event date.");
                            return;
                        }
                    }
                },
            ],
        ];
    }
}
