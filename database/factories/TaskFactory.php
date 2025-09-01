<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Project;
use App\Models\Status;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'project_id' => Project::factory(), // Creates project if none exists
            'title' => fake()->sentence(4),
            'description' => fake()->optional()->paragraph(),
            'status_id' => Status::inRandomOrder()->first()?->id ?? 1,
        ];
    }
}
