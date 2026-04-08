<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('active_role')->default('seller')->after('email');
            $table->json('roles')->default('["seller"]')->after('active_role');
        });

        // Migrate existing role data
        DB::statement("UPDATE users SET active_role = role, roles = json_build_array(role) WHERE role IS NOT NULL");

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('seller')->after('email');
        });

        DB::statement("UPDATE users SET role = active_role");

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['active_role', 'roles']);
        });
    }
};
