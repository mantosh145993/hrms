<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
       Schema::create('shifts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->time('start_time');              // e.g., 09:30:00
            $table->time('end_time');                // e.g., 18:30:00
            $table->unsignedSmallInteger('grace_minutes')->default(10);
            $table->unsignedSmallInteger('half_day_after_minutes')->default(240); // 4 hours
            $table->unsignedSmallInteger('workday_minutes')->default(480); // 8 hours
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shifts');
    }
};
