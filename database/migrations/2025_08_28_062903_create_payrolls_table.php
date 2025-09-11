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
        Schema::create('payrolls', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->cascadeOnDelete();
        $table->string('month', 7); // '2025-08'
        $table->unsignedInteger('working_days')->default(0);
        $table->unsignedInteger('present_days')->default(0);
        $table->unsignedInteger('late_minutes_total')->default(0);
        $table->unsignedInteger('overtime_minutes_total')->default(0);
        $table->unsignedInteger('paid_days')->default(0);
        $table->integer('gross_pay')->nullable(); // optional
        $table->integer('deductions')->nullable();
        $table->integer('net_pay')->nullable();
        $table->timestamps();
        $table->unique(['user_id','month']);
    });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payrolls');
    }
};
