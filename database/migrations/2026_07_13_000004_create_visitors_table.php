<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('visitors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('church_id')->constrained()->cascadeOnDelete();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('sex')->nullable();
            $table->unsignedInteger('age')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('marital_status')->nullable();
            $table->date('wedding_anniversary')->nullable();
            $table->string('phone');
            $table->string('email')->nullable();
            $table->string('city')->nullable();
            $table->text('residential_address')->nullable();
            $table->text('business_address')->nullable();
            $table->string('nearest_bus_stop')->nullable();
            $table->string('occupation')->nullable();
            $table->string('invited_by')->nullable();
            $table->string('invited_by_phone')->nullable();
            $table->string('invited_by_name')->nullable();
            $table->boolean('wants_membership')->default(false);
            $table->boolean('born_again')->default(false);
            $table->date('born_again_when')->nullable();
            $table->boolean('wants_counsel')->default(false);
            $table->string('preferred_visit_date', 160)->nullable();
            $table->boolean('is_baptized')->default(false);
            $table->string('status')->default('new');
            $table->timestamps();

            $table->index(['church_id', 'phone']);
            $table->index(['church_id', 'email']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visitors');
    }
};
