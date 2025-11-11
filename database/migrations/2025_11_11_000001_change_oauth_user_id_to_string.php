<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $connection = Schema::getConnection();
        $driver = $connection->getDriverName();

        if ($driver === 'pgsql') {
            // Change bigint -> varchar for Postgres (preserve existing values)
            DB::statement("ALTER TABLE oauth_access_tokens ALTER COLUMN user_id TYPE varchar USING user_id::varchar;");
        } else {
            // MySQL / MariaDB
            DB::statement("ALTER TABLE oauth_access_tokens MODIFY user_id varchar(255);");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $connection = Schema::getConnection();
        $driver = $connection->getDriverName();

        if ($driver === 'pgsql') {
            DB::statement("ALTER TABLE oauth_access_tokens ALTER COLUMN user_id TYPE bigint USING (user_id::bigint);");
        } else {
            DB::statement("ALTER TABLE oauth_access_tokens MODIFY user_id bigint unsigned;");
        }
    }
};
