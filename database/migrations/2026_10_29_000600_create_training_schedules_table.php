<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('training_schedules', function (Blueprint $table) {
            $table->id();
            // Nullable: weekend try-out entries span several age programs at once.
            $table->foreignId('age_program_id')->nullable()->constrained('age_programs')->nullOnDelete();
            $table->enum('day_of_week', ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Sabtu/Minggu']);
            $table->string('time_slot', 50);
            $table->string('target_category', 100);
            $table->enum('activity_type', ['Grassroot', 'Youth', 'Try Out Match / Turnamen']);
            $table->string('coach_name', 150);
            $table->timestamps();

            $table->index('day_of_week');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('training_schedules');
    }
};

