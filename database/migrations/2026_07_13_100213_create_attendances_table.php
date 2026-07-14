<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('visitor_id')->constrained('visitors')->cascadeOnDelete();
            $table->foreignId('church_service_id')->constrained('church_services')->cascadeOnDelete();
            $table->date('attended_on');
            $table->time('attended_at')->nullable();
            $table->foreignId('church_id')->nullable()->constrained('churches')->nullOnDelete();
            $table->timestamps();
            $table->unique(['visitor_id', 'church_service_id', 'attended_on'], 'attendance_unique');
        });
    }

    public function down()
    {
        Schema::dropIfExists('attendances');
    }
};
