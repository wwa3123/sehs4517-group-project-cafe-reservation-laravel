<?php

namespace App\Http\Requests;

use App\Models\Table;
use App\Models\TimeSlot;
use App\Services\EventService;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class StoreEventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'event_name'         => ['required', 'string', 'max:255'],
            'event_descriptions' => ['nullable', 'string', 'max:255'],
            'event_fee'          => ['required', 'integer', 'min:0', 'max:99999'],
            'max_participants'   => ['required', 'integer', 'min:1'],
            'event_date'         => ['required', 'date', 'after:now'],
            'num_guests'         => ['required', 'integer', 'min:1'],
            'table_id'           => ['required', 'array', 'min:1'],
            'table_id.*'         => ['exists:tables,table_id'],
            'time_slots_id'      => ['required', 'array', 'min:1'],
            'time_slots_id.*'    => [
                'distinct',
                'exists:time_slots,time_slots_id',
                function ($attribute, $value, $fail) {
                    $eventDate = Carbon::parse($this->input('event_date'))->toDateString();
                    $service   = app(EventService::class);

                    foreach ((array) $this->input('table_id') as $tableId) {
                        if ($service->isSlotBooked((int) $tableId, (int) $value, $eventDate)) {
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
