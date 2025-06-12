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
        Schema::create('equipment', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->foreignId('generation_id')->constrained()->onDelete('cascade');
            $table->foreignId('body_type_id')->constrained();
            $table->foreignId('engine_type_id')->constrained();
            $table->foreignId('transmission_type_id')->constrained();
            $table->foreignId('drive_type_id')->constrained();
            $table->foreignId('country_id')->constrained();
            $table->decimal('engine_volume', 3, 1);
            $table->integer('engine_power');
            $table->text('description');
            $table->integer('range');
            $table->integer('max_speed');
            $table->string('model_path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipment');
    }
};
