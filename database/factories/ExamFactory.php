<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Classroom;
use App\Models\Course;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Exam>
 */
class ExamFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'classrooms_id' => Classroom::factory(),
            'courses_id' => Course::factory(),
            'study_period' => '2024/2025',
            'session' => fake()->unique()->numberBetween(1, 16),
            'type' => fake()->unique()->randomElement(['UTS', 'UAS', 'Quiz', 'Entrance']),
        ];
    }
}
