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
        Schema::table('holidays', function (Blueprint $table) {
            $table->date('end_date')->nullable()->after('date');
            $table->unsignedInteger('max_days')->default(1)->after('end_date');
        });
    }

    /**
     * Reverse the migrations.
     */
     public function down(): void
    {
        Schema::table('holidays', function (Blueprint $table) {
            $table->dropColumn(['end_date', 'max_days']);
        });
    }
};
