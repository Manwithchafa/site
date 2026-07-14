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
            $table->string('gender');
            $table->date('date_of_birth')->nullable();
            $table->string('phone');
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->string('nearest_bus_stop')->nullable();
            $table->string('occupation')->nullable();
            $table->string('invited_by')->nullable();
            $table->boolean('born_again')->default(false);
            $table->boolean('wants_membership')->default(false);
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
