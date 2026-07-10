<?php

namespace Tests\Feature;

use App\Models\Branch;
use App\Models\Department;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BranchPolicyTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_any_branch(): void
    {
        $adminUser = User::factory()->create();
        $adminBranch = Branch::factory()->create();
        $adminDept = Department::factory()->create(['branch_id' => $adminBranch->id]);
        Employee::factory()->create([
            'user_id' => $adminUser->id,
            'department_id' => $adminDept->id,
            'role' => 'admin',
        ]);

        $otherBranch = Branch::factory()->create();

        $response = $this->actingAs($adminUser)->get("/branches/{$otherBranch->id}");

        $response->assertOk();
    }

    public function test_manager_can_view_their_own_branch(): void
    {
        $branch = Branch::factory()->create();
        $department = Department::factory()->create(['branch_id' => $branch->id]);
        $managerUser = User::factory()->create();
        Employee::factory()->create([
            'user_id' => $managerUser->id,
            'department_id' => $department->id,
            'role' => 'manager',
        ]);

        $response = $this->actingAs($managerUser)->get("/branches/{$branch->id}");

        $response->assertOk();
    }

    public function test_manager_cannot_view_a_different_branch(): void
    {
        $ownBranch = Branch::factory()->create();
        $ownDept = Department::factory()->create(['branch_id' => $ownBranch->id]);
        $managerUser = User::factory()->create();
        Employee::factory()->create([
            'user_id' => $managerUser->id,
            'department_id' => $ownDept->id,
            'role' => 'manager',
        ]);

        $otherBranch = Branch::factory()->create();

        $response = $this->actingAs($managerUser)->get("/branches/{$otherBranch->id}");

        $response->assertForbidden();
    }

    public function test_guest_cannot_view_branches_at_all(): void
    {
        $branch = Branch::factory()->create();

        $response = $this->get("/branches/{$branch->id}");

        $response->assertRedirect('/login');
    }
}
