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
        Schema::table('bookings', function (Blueprint $table) {
            // Изменяем поле appointment_date, делая его nullable
            $table->timestamp('appointment_date')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // Возвращаем обратно NOT NULL (если потребуется откат)
            $table->timestamp('appointment_date')->nullable(false)->change();
        });
    }
};