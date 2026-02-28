<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $driver = DB::getDriverName();

        if ($driver === 'pgsql') {
            DB::statement('ALTER TABLE pins ALTER COLUMN image_url DROP NOT NULL');
            return;
        }

        if ($driver === 'mysql' || $driver === 'mariadb') {
            DB::statement('ALTER TABLE pins MODIFY image_url TEXT NULL');
            return;
        }

        // SQLite (used in tests) does not support this ALTER COLUMN syntax.
        // The migration is a no-op there to keep tests portable.
    }

    public function down(): void
    {
        $driver = DB::getDriverName();

        if ($driver === 'pgsql') {
            DB::statement('ALTER TABLE pins ALTER COLUMN image_url SET NOT NULL');
            return;
        }

        if ($driver === 'mysql' || $driver === 'mariadb') {
            DB::statement('ALTER TABLE pins MODIFY image_url TEXT NOT NULL');
            return;
        }

        // SQLite no-op for symmetry with up().
    }
};
