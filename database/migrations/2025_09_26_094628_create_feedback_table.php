<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('feedbacks')) {
            Schema::create('feedbacks', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('employee_id'); // Foreign key
                $table->string('title');                   // Short title/subject
                $table->text('message');                   // Feedback content
                $table->enum('type', ['positive', 'negative', 'neutral'])->default('neutral'); // Feedback type
                $table->unsignedBigInteger('given_by')->nullable(); // Feedback given by (maybe manager/user)
                $table->timestamps();

                // Relations
                $table->foreign('employee_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('given_by')->references('id')->on('users')->onDelete('set null');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('feedbacks');
    }
};
