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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('car_id')->constrained()->onDelete('cascade');
            $table->timestamp('booking_date')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('appointment_date'); // Дата визита в автосалон
            $table->enum('status', ['pending', 'confirmed', 'rejected', 'completed'])->default('pending');
            $table->text('manager_comment')->nullable();
            $table->timestamps();
            $table->index(['car_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
