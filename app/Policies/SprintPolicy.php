<?php

namespace App\Policies;

use App\Models\Sprint;
use App\Models\Project;
use App\Models\User;

class SprintPolicy
{
    public function view(User $user, Sprint $sprint): bool
    {
        return $user->can('view', $sprint->project);
    }

    public function update(User $user, Sprint $sprint): bool
    {
        return $user->can('update', $sprint->project);
    }


    public function delete(User $user, Sprint $sprint): bool
    {
        return $user->can('update', $sprint->project);
    }
}