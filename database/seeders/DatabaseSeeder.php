<?php

namespace Database\Seeders;

use App\Models\Classroom;
use App\Models\User;
use Database\Factories\AdminFactory;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Database\Factories\ClassroomFactory;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        Classroom::factory(3)
            ->has(User::factory()->count(3), 'students') // Create 3 users for each classroom
            ->create();


        AdminFactory::new()->create([
            'name' => 'admin'
        ]);

    }
}
