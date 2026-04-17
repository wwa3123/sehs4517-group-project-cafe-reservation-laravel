<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->integer('discount_tokens_used')->default(0)->after('num_guests');
            $table->decimal('discount_amount_saved', 10, 2)->default(0)->after('discount_tokens_used');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->dropColumn(['discount_tokens_used', 'discount_amount_saved']);
        });
    }
};
