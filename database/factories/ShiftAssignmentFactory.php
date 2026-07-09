<?php

namespace Database\Factories;

use App\Models\ShiftAssignment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ShiftAssignment>
 */
class ShiftAssignmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'employee_id' => \App\Models\Employee::factory(),
            'shift_id' => \App\Models\Shift::factory(),
            'date' => fake()->dateTimeBetween('-90 days', 'now')->format('Y-m-d'),
        ];
    }
}
