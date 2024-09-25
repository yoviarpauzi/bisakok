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
        Schema::create('user_sheets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('users')->constrained('users')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('exams')->constrained('exams')->cascadeOnUpdate()->cascadeOnDelete();
            $table->integer('num_of_question');
            $table->integer('num_of_question_answered');
            $table->integer('num_of_correct_answer');
            $table->integer('num_of_wrong_answer');
            $table->decimal('score', 4, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_sheets');
    }
};
