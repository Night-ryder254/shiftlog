<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Branch;

class BranchPolicy
{
    /**
     * Admins can view any branch. Managers/staff can only view
     * the branch tied to their own employee record.
     */
    public function view(User $user, Branch $branch): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->employee?->department?->branch_id === $branch->id;
    }

    /**
     * Only admins can create branches.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Only admins can update branch details.
     */
    public function update(User $user, Branch $branch): bool
    {
        return $user->isAdmin();
    }

    /**
     * Only admins can delete branches.
     */
    public function delete(User $user, Branch $branch): bool
    {
        return $user->isAdmin();
    }
}
