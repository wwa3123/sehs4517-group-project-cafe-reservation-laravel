<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reserved_slots', function (Blueprint $table) {
            $table->dropForeign(['reservation_id']);
            $table->unsignedBigInteger('event_id')->nullable()->after('reservation_id');
            $table->foreign('event_id')->references('event_id')->on('events')->onDelete('cascade');
            $table->unsignedBigInteger('reservation_id')->nullable()->change();
            $table->foreign('reservation_id')->references('reservation_id')->on('reservations')->onDelete('cascade');
        });

        DB::table('reservations')
            ->whereNotNull('reservations.event_id')
            ->select(['reservation_id', 'event_id'])
            ->orderBy('reservation_id')
            ->each(function (object $reservation): void {
                DB::table('reserved_slots')
                    ->where('reservation_id', $reservation->reservation_id)
                    ->update([
                        'event_id' => $reservation->event_id,
                        'reservation_id' => null,
                        'source_type' => 'EVENT',
                    ]);
            });

        DB::table('loyalty_txns')
            ->where('reference_type', 'Reservation')
            ->whereIn('reference_id', DB::table('reservations')->whereNotNull('event_id')->pluck('reservation_id'))
            ->delete();

        DB::table('reservations')->whereNotNull('event_id')->delete();

        Schema::table('reservations', function (Blueprint $table) {
            $table->dropForeign(['event_id']);
            $table->dropColumn('event_id');
        });

        DB::table('members')
            ->where('role', 'system')
            ->where('email', 'like', 'event-%@system.local')
            ->delete();
    }

    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->unsignedBigInteger('event_id')->nullable()->after('member_id');
            $table->foreign('event_id')->references('event_id')->on('events')->onDelete('set null');
        });

        DB::table('events')
            ->select(['event_id', 'event_name', 'max_participants'])
            ->orderBy('event_id')
            ->each(function (object $event): void {
                $eventSlots = DB::table('reserved_slots')
                    ->where('event_id', $event->event_id)
                    ->get()
                    ->groupBy(fn (object $slot) => $slot->table_id.':'.$slot->reservation_date);

                if ($eventSlots->isEmpty()) {
                    return;
                }

                $memberId = DB::table('members')->insertGetId([
                    'email' => 'event-'.$event->event_id.'@system.local',
                    'first_name' => $event->event_name,
                    'last_name' => '(Event)',
                    'password_hash' => Hash::make(Str::random(40)),
                    'role' => 'system',
                    'subscribe_events' => false,
                    'loyalty_points' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $eventSlots->each(function ($slots) use ($event, $memberId): void {
                    $firstSlot = $slots->first();
                    $reservationId = DB::table('reservations')->insertGetId([
                        'member_id' => $memberId,
                        'event_id' => $event->event_id,
                        'date' => $firstSlot->reservation_date,
                        'num_guests' => $event->max_participants,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    DB::table('reserved_slots')
                        ->whereIn('reserved_slots_id', $slots->pluck('reserved_slots_id'))
                        ->update([
                            'reservation_id' => $reservationId,
                            'event_id' => null,
                            'source_type' => 'RESERVATION',
                        ]);
                });
            });

        Schema::table('reserved_slots', function (Blueprint $table) {
            $table->dropForeign(['event_id']);
            $table->dropColumn('event_id');
            $table->dropForeign(['reservation_id']);
            $table->unsignedBigInteger('reservation_id')->nullable(false)->change();
            $table->foreign('reservation_id')->references('reservation_id')->on('reservations')->onDelete('cascade');
        });
    }
};
