<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Branch;
use App\Models\Department;
use App\Models\User;
use App\Models\Employee;
use App\Models\Shift;
use App\Models\ShiftAssignment;
use App\Models\AttendanceRecord;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create 4 branches
        $branches = Branch::factory(4)->create();

        // 2. Each branch gets 4 departments
        $departments = collect();
        foreach ($branches as $branch) {
            $depts = Department::factory(4)->create(['branch_id' => $branch->id]);
            $departments = $departments->merge($depts);
        }

        // 3. Each branch gets 2-4 shift templates
        $shifts = collect();
        foreach ($branches as $branch) {
            $branchShifts = Shift::factory(3)->create(['branch_id' => $branch->id]);
            $shifts = $shifts->merge($branchShifts);
        }

        // 4. Create ~500 employees, each tied to a real department (and thus a real branch)
        $employees = collect();
        foreach (range(1, 500) as $i) {
            $user = User::factory()->create();
            $department = $departments->random();
            $employees->push(Employee::factory()->create([
                'user_id' => $user->id,
                'department_id' => $department->id,
            ]));
        }

        // 5. Create shift assignments: each employee gets assigned shifts
        //    from THEIR OWN branch only (not a random branch)
        foreach ($employees as $employee) {
            $employeeBranchId = $employee->department->branch_id;
            $branchShifts = $shifts->where('branch_id', $employeeBranchId);

            // assign ~10 shift dates per employee over the last 90 days
            foreach (range(1, 10) as $i) {
                $assignment = ShiftAssignment::factory()->create([
                    'employee_id' => $employee->id,
                    'shift_id' => $branchShifts->random()->id,
                ]);

                // 6. Every assignment gets an attendance record
                AttendanceRecord::factory()->create([
                    'shift_assignment_id' => $assignment->id,
                ]);
            }
        }
    }
}
