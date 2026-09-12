<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add the reservation date to the slot record so availability can be
     * protected by a single database-level unique constraint.
     */
    public function up(): void
    {
        if (Schema::hasColumn('reserved_slots', 'reservation_date')) {
            return;
        }

        Schema::table('reserved_slots', function (Blueprint $table) {
            $table->date('reservation_date')->nullable();
        });

        DB::table('reserved_slots')
            ->orderBy('reserved_slots_id')
            ->each(function (object $slot): void {
                $date = DB::table('reservations')
                    ->where('reservation_id', $slot->reservation_id)
                    ->value('date');

                DB::table('reserved_slots')
                    ->where('reserved_slots_id', $slot->reserved_slots_id)
                    ->update(['reservation_date' => Carbon::parse($date)->toDateString()]);
            });

        Schema::table('reserved_slots', function (Blueprint $table) {
            $table->date('reservation_date')->nullable(false)->change();
            $table->unique(['table_id', 'time_slots_id', 'reservation_date']);
        });
    }

    /**
     * Reverse the slot-date constraint.
     */
    public function down(): void
    {
        if (! Schema::hasColumn('reserved_slots', 'reservation_date')) {
            return;
        }

        Schema::table('reserved_slots', function (Blueprint $table) {
            $table->dropUnique(['table_id', 'time_slots_id', 'reservation_date']);
            $table->dropColumn('reservation_date');
        });
    }
};
