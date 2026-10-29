<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('galleries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('uploaded_by')->constrained('users')->restrictOnDelete();
            $table->string('title', 150);
            $table->string('caption', 255)->nullable();
            $table->string('image_path', 255);
            $table->enum('grid_ratio', ['landscape', 'portrait', 'square'])->default('landscape');
            $table->boolean('is_highlighted_on_home')->default(false)->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('galleries');
    }
};

