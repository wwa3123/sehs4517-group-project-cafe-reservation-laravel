<?php

namespace App\Http\Controllers;

use App\Models\Table;
use App\Models\TimeSlot;
use App\Models\ReservedSlot;
use Carbon\Carbon;
use Illuminate\Http\Request;

class OccupancyController extends Controller
{
    public function index()
    {
        return view('occupancy');
    }

    /**
     * Return current occupancy as JSON for live polling.
     * A table is "occupied" when it has a reserved slot whose time slot
     * spans the current time on today's date.
     */
    public function data()
    {
        $now   = Carbon::now();
        $today = $now->toDateString();

        $tables = Table::orderBy('type')->orderBy('table_id')->get();

        // Find all time slot IDs that are active right now
        $activeSlotIds = TimeSlot::whereTime('start_time', '<=', $now->format('H:i:s'))
            ->whereTime('end_time',   '>',  $now->format('H:i:s'))
            ->pluck('time_slots_id');

        // Reserved slot table_ids for today + active slots
        $occupiedTableIds = ReservedSlot::whereIn('time_slots_id', $activeSlotIds)
            ->whereHas('reservation', fn($q) => $q->whereDate('date', $today))
            ->pluck('table_id')
            ->unique();

        $totalTables    = $tables->count();
        $occupiedCount  = $occupiedTableIds->count();
        $availableCount = $totalTables - $occupiedCount;
        $occupancyPct   = $totalTables > 0 ? round($occupiedCount / $totalTables * 100) : 0;

        $tableList = $tables->map(fn($t) => [
            'table_id' => $t->table_id,
            'name'     => $t->name,
            'type'     => $t->type,
            'capacity' => $t->capacity,
            'occupied' => $occupiedTableIds->contains($t->table_id),
        ]);

        return response()->json([
            'as_of'          => $now->format('g:i A'),
            'total'          => $totalTables,
            'occupied'       => $occupiedCount,
            'available'      => $availableCount,
            'occupancy_pct'  => $occupancyPct,
            'tables'         => $tableList,
        ]);
    }
}
