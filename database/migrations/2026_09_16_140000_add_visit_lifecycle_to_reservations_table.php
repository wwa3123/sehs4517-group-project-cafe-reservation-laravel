<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->string('status', 20)->default('confirmed')->index()->after('num_guests');
            $table->timestamp('checked_in_at')->nullable()->after('status');
            $table->timestamp('completed_at')->nullable()->after('checked_in_at');
            $table->timestamp('cancelled_at')->nullable()->after('completed_at');
            $table->timestamp('no_show_at')->nullable()->after('cancelled_at');
        });
    }

    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropColumn(['status', 'checked_in_at', 'completed_at', 'cancelled_at', 'no_show_at']);
        });
    }
};
