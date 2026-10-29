<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('registration_fees', function (Blueprint $table) {
            $table->id();
            $table->string('label', 100);
            $table->string('amount_text', 100);
            $table->enum('fee_type', ['one_time', 'monthly']);
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['is_active', 'fee_type', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('registration_fees');
    }
};

