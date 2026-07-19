<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('visitors', 'preferred_visit_date')) {
            return;
        }

        match (DB::connection()->getDriverName()) {
            'pgsql' => DB::statement('ALTER TABLE visitors ALTER COLUMN preferred_visit_date TYPE varchar(160) USING preferred_visit_date::varchar'),
            'mysql', 'mariadb' => DB::statement('ALTER TABLE visitors MODIFY preferred_visit_date varchar(160) NULL'),
            default => null,
        };
    }

    public function down(): void
    {
        if (! Schema::hasColumn('visitors', 'preferred_visit_date')) {
            return;
        }

        match (DB::connection()->getDriverName()) {
            'pgsql' => DB::statement("ALTER TABLE visitors ALTER COLUMN preferred_visit_date TYPE date USING NULLIF(preferred_visit_date, '')::date"),
            'mysql', 'mariadb' => DB::statement('ALTER TABLE visitors MODIFY preferred_visit_date date NULL'),
            default => null,
        };
    }
};
