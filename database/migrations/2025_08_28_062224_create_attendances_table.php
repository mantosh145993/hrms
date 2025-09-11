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
        if (!Schema::hasTable('attendances')) {
            Schema::create('attendances', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->date('work_date');
                $table->dateTime('check_in_at')->nullable();
                $table->dateTime('check_out_at')->nullable();
                $table->integer('late_minutes')->default(0);
                $table->integer('worked_minutes')->default(0);
                $table->enum('status', ['present', 'absent', 'half_day', 'on_leave'])->default('present');
                $table->json('meta')->nullable(); // device/ip, notes, manual edit reason
                $table->timestamps();
                $table->unique(['user_id', 'work_date']); // one row per day per user
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
