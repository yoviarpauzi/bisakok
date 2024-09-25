<?php

namespace Database\Factories;

use App\Models\Exam;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ExamSession>
 */
class ExamSessionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $start_at = fake()->dateTime();
        $end_at = $start_at->modify('+1 hour');

        return [
            'exams_id' => Exam::factory(),
            'start_at' => $start_at,
            'end_at' => $end_at
        ];
    }
}
