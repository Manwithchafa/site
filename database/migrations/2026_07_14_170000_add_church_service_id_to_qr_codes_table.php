<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('qr_codes', function (Blueprint $table) {
            if (! Schema::hasColumn('qr_codes', 'church_service_id')) {
                $table->foreignId('church_service_id')
                    ->nullable()
                    ->after('church_id')
                    ->constrained('church_services')
                    ->onDelete('cascade');
            }
        });
    }

    public function down(): void
    {
        Schema::table('qr_codes', function (Blueprint $table) {
            if (Schema::hasColumn('qr_codes', 'church_service_id')) {
                $table->dropForeign(['church_service_id']);
                $table->dropColumn('church_service_id');
            }
        });
    }
};
