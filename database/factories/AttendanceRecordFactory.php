<?php

namespace Database\Factories;

use App\Models\AttendanceRecord;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AttendanceRecord>
 */
class AttendanceRecordFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $status = fake()->randomElement(['on_time', 'on_time', 'on_time', 'late', 'absent']); // 60% on time, 20% late, 20% absent

        return [
            'shift_assignment_id' => \App\Models\ShiftAssignment::factory(),
            'clock_in' => $status === 'absent' ? null : fake()->dateTimeBetween('-90 days', 'now'),
            'clock_out' => $status === 'absent' ? null : fake()->dateTimeBetween('-90 days', 'now'),
            'status' => $status,
        ];
    }
}
