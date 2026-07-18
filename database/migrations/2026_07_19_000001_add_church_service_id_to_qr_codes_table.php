<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('qr_codes', 'church_service_id')) {
            Schema::table('qr_codes', function (Blueprint $table) {
                $table->foreignId('church_service_id')
                    ->nullable()
                    ->constrained('church_services')
                    ->nullOnDelete()
                    ->after('church_id');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('qr_codes', 'church_service_id')) {
            Schema::table('qr_codes', function (Blueprint $table) {
                $table->dropConstrainedForeignId('church_service_id');
            });
        }
    }
};
