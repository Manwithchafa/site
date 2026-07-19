<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('visitor_registrations', function (Blueprint $table) {
            if (! Schema::hasColumn('visitor_registrations', 'church_service_id')) {
                $table->foreignId('church_service_id')
                    ->nullable()
                    ->after('church_id')
                    ->constrained('church_services')
                    ->nullOnDelete();
            }

            if (! Schema::hasColumn('visitor_registrations', 'registered_on')) {
                $table->date('registered_on')->nullable()->after('qr_code_id');
            }

            if (! Schema::hasColumn('visitor_registrations', 'registered_at')) {
                $table->time('registered_at')->nullable()->after('registered_on');
            }
        });

        if (Schema::hasColumn('visitor_registrations', 'church_service_id')) {
            DB::table('visitor_registrations')
                ->whereNull('visitor_registrations.church_service_id')
                ->whereExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('qr_codes')
                        ->whereColumn('qr_codes.id', 'visitor_registrations.qr_code_id')
                        ->whereNotNull('qr_codes.church_service_id');
                })
                ->update([
                    'church_service_id' => DB::raw(
                        '(select church_service_id from qr_codes where qr_codes.id = visitor_registrations.qr_code_id limit 1)'
                    ),
                ]);
        }

        if (Schema::hasColumn('visitor_registrations', 'registered_on')) {
            DB::table('visitor_registrations')
                ->whereNull('registered_on')
                ->update(['registered_on' => DB::raw('DATE(created_at)')]);
        }

        if (Schema::hasColumn('visitor_registrations', 'registered_at')) {
            $timeExpression = match (DB::connection()->getDriverName()) {
                'pgsql' => 'created_at::time',
                default => 'TIME(created_at)',
            };

            DB::table('visitor_registrations')
                ->whereNull('registered_at')
                ->update(['registered_at' => DB::raw($timeExpression)]);
        }
    }

    public function down(): void
    {
        Schema::table('visitor_registrations', function (Blueprint $table) {
            if (Schema::hasColumn('visitor_registrations', 'registered_at')) {
                $table->dropColumn('registered_at');
            }

            if (Schema::hasColumn('visitor_registrations', 'registered_on')) {
                $table->dropColumn('registered_on');
            }

            if (Schema::hasColumn('visitor_registrations', 'church_service_id')) {
                $table->dropConstrainedForeignId('church_service_id');
            }
        });
    }
};
