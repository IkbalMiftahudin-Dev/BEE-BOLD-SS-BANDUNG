<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Additive migration so the already-migrated users table keeps its data.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Least-privileged role by default to avoid accidental admin escalation.
            $table->enum('role', ['admin', 'editor'])->default('editor')->after('password')->index();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['role']);
            $table->dropColumn('role');
        });
    }
};

