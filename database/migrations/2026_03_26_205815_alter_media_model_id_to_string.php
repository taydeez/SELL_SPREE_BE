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
        // SQLite re-creates the table correctly from the updated create migration;
        // the column is already char(26) in a fresh SQLite run, so nothing to do.
        if (DB::getDriverName() !== 'pgsql') {
            return;
        }

        Schema::table('media', function (Blueprint $table) {
            $table->dropIndex(['model_type', 'model_id']);
        });

        DB::statement('ALTER TABLE media ALTER COLUMN model_id TYPE CHAR(26) USING model_id::TEXT');

        Schema::table('media', function (Blueprint $table) {
            $table->index(['model_type', 'model_id']);
        });
    }

    public function down(): void
    {
        if (DB::getDriverName() !== 'pgsql') {
            return;
        }

        Schema::table('media', function (Blueprint $table) {
            $table->dropIndex(['model_type', 'model_id']);
        });

        DB::statement('ALTER TABLE media ALTER COLUMN model_id TYPE BIGINT USING model_id::BIGINT');

        Schema::table('media', function (Blueprint $table) {
            $table->index(['model_type', 'model_id']);
        });
    }
};
