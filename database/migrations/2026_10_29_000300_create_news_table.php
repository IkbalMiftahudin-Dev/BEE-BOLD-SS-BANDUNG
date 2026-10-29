<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            // Restrict deletes so published articles never lose their category or author silently.
            $table->foreignId('category_id')->constrained('news_categories')->restrictOnDelete();
            $table->foreignId('author_id')->constrained('users')->restrictOnDelete();
            $table->string('title', 200);
            $table->string('slug', 220)->unique();
            $table->text('lead_paragraph');
            $table->longText('content');
            $table->string('thumbnail_path', 255);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_published')->default(false);
            $table->dateTime('published_at')->nullable();
            $table->timestamps();

            $table->index(['is_published', 'published_at']);
            $table->index('is_featured');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('news');
    }
};

