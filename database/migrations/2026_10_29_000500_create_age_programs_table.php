<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('age_programs', function (Blueprint $table) {
            $table->id();
            $table->string('title', 100);
            $table->enum('category_group', ['Grassroot', 'Youth']);
            $table->string('age_range', 50);
            $table->string('coach_name', 100);
            $table->text('description');
            $table->string('image_path', 255);
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['is_active', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('age_programs');
    }
};

