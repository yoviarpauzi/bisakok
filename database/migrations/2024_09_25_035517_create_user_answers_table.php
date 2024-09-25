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
        Schema::create('user_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('users_id')->constrained('users')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('exams_id')->constrained('exams')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('questions_id')->constrained('questions')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('choices_id')->constrained('choices')->cascadeOnUpdate()->cascadeOnDelete();
            $table->unique(['users_id', 'exams_id', 'questions_id', 'choices_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_answers');
    }
};
