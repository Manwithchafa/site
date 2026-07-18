<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('qr_codes', 'church_service_id') && Schema::getConnection()->getDriverName() !== 'sqlite') {
            Schema::table('qr_codes', function (Blueprint $table) {
                $table->dropForeign(['church_service_id']);
                $table->dropColumn('church_service_id');
            });
        }

        if (Schema::hasColumn('visitor_registrations', 'church_service_id') && Schema::getConnection()->getDriverName() !== 'sqlite') {
            try {
                Schema::table('visitor_registrations', function (Blueprint $table) {
                    $table->dropIndex(['church_service_id', 'registered_on']);
                });
            } catch (\Throwable) {
            }

            Schema::table('visitor_registrations', function (Blueprint $table) {
                $table->dropForeign(['church_service_id']);
                $table->dropColumn('church_service_id');
            });
        }

        if (Schema::hasColumn('visitor_registrations', 'registered_on') && Schema::getConnection()->getDriverName() !== 'sqlite') {
            Schema::table('visitor_registrations', function (Blueprint $table) {
                $table->dropColumn('registered_on');
            });
        }

        if (Schema::hasColumn('visitor_registrations', 'registered_at') && Schema::getConnection()->getDriverName() !== 'sqlite') {
            Schema::table('visitor_registrations', function (Blueprint $table) {
                $table->dropColumn('registered_at');
            });
        }
    }

    public function down(): void
    {
        if (! Schema::hasColumn('qr_codes', 'church_service_id')) {
            Schema::table('qr_codes', function (Blueprint $table) {
                $table->foreignId('church_service_id')->nullable()->constrained()->cascadeOnDelete();
            });
        }

        if (! Schema::hasColumn('visitor_registrations', 'church_service_id')) {
            Schema::table('visitor_registrations', function (Blueprint $table) {
                $table->foreignId('church_service_id')->nullable()->constrained()->cascadeOnDelete();
            });
        }

        if (! Schema::hasColumn('visitor_registrations', 'registered_on')) {
            Schema::table('visitor_registrations', function (Blueprint $table) {
                $table->date('registered_on')->nullable();
            });
        }

        if (! Schema::hasColumn('visitor_registrations', 'registered_at')) {
            Schema::table('visitor_registrations', function (Blueprint $table) {
                $table->time('registered_at')->nullable();
            });
        }
    }
};
