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
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equipment_id')->constrained();
            $table->string('vin', 17)->unique();
            $table->integer('mileage');
            $table->decimal('price', 12, 2);
            $table->text('description')->nullable();
            $table->boolean('is_sold')->default(false);
            $table->foreignId('branch_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('color_id')->nullable()->constrained()->onDelete('set null');
            $table->string('custom_color_name', 50)->nullable();
            $table->string('custom_color_hex', 7)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};
