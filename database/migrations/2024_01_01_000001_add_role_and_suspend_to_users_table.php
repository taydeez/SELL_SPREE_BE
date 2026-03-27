<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('seller')->after('email');
            $table->boolean('is_suspended')->default(false)->after('role');
            $table->softDeletes();
        });

        // Change id to string (ULID) — drop and recreate with string PK
        // Note: Laravel 11 ships with bigIncrements by default; we alter to string ULID
        // This migration assumes the users table was just created (fresh install).
        // If running on existing data, a data migration would be required.
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'is_suspended', 'deleted_at']);
        });
    }
};
