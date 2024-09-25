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
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('classrooms_id')->nullable()->constrained('classrooms')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('courses_id')->nullable()->constrained('courses')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('study_period', 10);
            $table->integer('session')->nullable();
            $table->enum('type', ['UTS', 'UAS', 'Quiz', 'Entrance']);
            $table->unique(['classrooms_id', 'courses_id', 'study_period', 'session', 'type']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exams');
    }
};
