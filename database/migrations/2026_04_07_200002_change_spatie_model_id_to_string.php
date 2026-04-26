<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // model_has_roles
        DB::statement('ALTER TABLE model_has_roles DROP CONSTRAINT model_has_roles_pkey');
        DB::statement('DROP INDEX IF EXISTS model_has_roles_model_id_model_type_index');
        DB::statement('ALTER TABLE model_has_roles ALTER COLUMN model_id TYPE VARCHAR(26) USING model_id::VARCHAR');
        DB::statement('CREATE INDEX model_has_roles_model_id_model_type_index ON model_has_roles (model_id, model_type)');
        DB::statement('ALTER TABLE model_has_roles ADD PRIMARY KEY (role_id, model_id, model_type)');

        // model_has_permissions
        DB::statement('ALTER TABLE model_has_permissions DROP CONSTRAINT model_has_permissions_pkey');
        DB::statement('DROP INDEX IF EXISTS model_has_permissions_model_id_model_type_index');
        DB::statement('ALTER TABLE model_has_permissions ALTER COLUMN model_id TYPE VARCHAR(26) USING model_id::VARCHAR');
        DB::statement('CREATE INDEX model_has_permissions_model_id_model_type_index ON model_has_permissions (model_id, model_type)');
        DB::statement('ALTER TABLE model_has_permissions ADD PRIMARY KEY (permission_id, model_id, model_type)');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE model_has_roles DROP CONSTRAINT IF EXISTS model_has_roles_pkey');
        DB::statement('DROP INDEX IF EXISTS model_has_roles_model_id_model_type_index');
        DB::statement('ALTER TABLE model_has_roles ALTER COLUMN model_id TYPE BIGINT USING model_id::BIGINT');
        DB::statement('CREATE INDEX model_has_roles_model_id_model_type_index ON model_has_roles (model_id, model_type)');
        DB::statement('ALTER TABLE model_has_roles ADD PRIMARY KEY (role_id, model_id, model_type)');

        DB::statement('ALTER TABLE model_has_permissions DROP CONSTRAINT IF EXISTS model_has_permissions_pkey');
        DB::statement('DROP INDEX IF EXISTS model_has_permissions_model_id_model_type_index');
        DB::statement('ALTER TABLE model_has_permissions ALTER COLUMN model_id TYPE BIGINT USING model_id::BIGINT');
        DB::statement('CREATE INDEX model_has_permissions_model_id_model_type_index ON model_has_permissions (model_id, model_type)');
        DB::statement('ALTER TABLE model_has_permissions ADD PRIMARY KEY (permission_id, model_id, model_type)');
    }
};
