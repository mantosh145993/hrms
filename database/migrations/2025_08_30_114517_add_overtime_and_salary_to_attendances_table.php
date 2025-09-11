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
        Schema::table('attendances', function (Blueprint $table) {
            $table->integer('late_minutes')->default(0)->after('check_out_at'); // late arrival in minutes
            $table->integer('overtime_minutes')->default(0)->after('late_minutes'); // overtime in minutes
            $table->decimal('salary_deduction', 10, 2)->default(0)->after('overtime_minutes'); // salary deduction if late
        });
    }
    }

    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropColumn(['late_minutes', 'overtime_minutes', 'salary_deduction']);
        });
    }
};
