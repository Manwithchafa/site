<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('visitors', function (Blueprint $table) {
            if (! Schema::hasColumn('visitors', 'sex')) {
                $table->string('sex')->nullable()->after('last_name');
            }

            if (! Schema::hasColumn('visitors', 'age')) {
                $table->unsignedInteger('age')->nullable()->after('sex');
            }

            if (! Schema::hasColumn('visitors', 'marital_status')) {
                $table->string('marital_status')->nullable()->after('age');
            }

            if (! Schema::hasColumn('visitors', 'wedding_anniversary')) {
                $table->date('wedding_anniversary')->nullable()->after('marital_status');
            }

            if (! Schema::hasColumn('visitors', 'city')) {
                $table->string('city')->nullable()->after('email');
            }

            if (! Schema::hasColumn('visitors', 'residential_address')) {
                $table->text('residential_address')->nullable()->after('address');
            }

            if (! Schema::hasColumn('visitors', 'business_address')) {
                $table->text('business_address')->nullable()->after('residential_address');
            }

            if (! Schema::hasColumn('visitors', 'invited_by_name')) {
                $table->string('invited_by_name')->nullable()->after('invited_by');
            }

            if (! Schema::hasColumn('visitors', 'invited_by_phone')) {
                $table->string('invited_by_phone')->nullable()->after('invited_by_name');
            }

            if (! Schema::hasColumn('visitors', 'is_baptized')) {
                $table->boolean('is_baptized')->default(false)->after('born_again_when');
            }

            if (! Schema::hasColumn('visitors', 'wants_counsel')) {
                $table->boolean('wants_counsel')->default(false)->after('wants_membership');
            }

            if (! Schema::hasColumn('visitors', 'preferred_visit_date')) {
                $table->string('preferred_visit_date', 160)->nullable()->after('wants_counsel');
            }
        });
    }

    public function down(): void
    {
        Schema::table('visitors', function (Blueprint $table) {
            foreach ([
                'preferred_visit_date',
                'wants_counsel',
                'is_baptized',
                'invited_by_phone',
                'invited_by_name',
                'business_address',
                'residential_address',
                'city',
                'wedding_anniversary',
                'marital_status',
                'age',
                'sex',
            ] as $column) {
                if (Schema::hasColumn('visitors', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
