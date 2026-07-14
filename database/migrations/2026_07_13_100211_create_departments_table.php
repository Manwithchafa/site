<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('church_id')->constrained('churches')->cascadeOnDelete();
            $table->string('name');
            $table->string('slug')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->unique(['church_id', 'name']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('departments');
    }
};
