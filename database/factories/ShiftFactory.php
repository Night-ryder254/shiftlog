<?php

namespace Database\Factories;

use App\Models\Shift;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Shift>
 */
class ShiftFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $shiftType = fake()->randomElement(['Morning', 'Afternoon', 'Night']);
        $times = [
            'Morning' => ['07:00:00', '15:00:00'],
            'Afternoon' => ['15:00:00', '23:00:00'],
            'Night' => ['23:00:00', '07:00:00'],
        ];

        return [
            'branch_id' => \App\Models\Branch::factory(),
            'label' => $shiftType,
            'start_time' => $times[$shiftType][0],
            'end_time' => $times[$shiftType][1],
        ];
    }
}
