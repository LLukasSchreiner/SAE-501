<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;

class ProjectPolicy
{
    public function viewAny(User $user): bool
    {

        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function view(User $user, Project $project)
    {
        return $project->users->contains($user->id);
    }

    public function update(User $user, Project $project)
    {
        $role = $project->users()->where('user_id', $user->id)->first()?->pivot->role;
        return in_array($role, ['owner', 'manager']);
    }

    public function delete(User $user, Project $project)
    {
        return $project->owner_id === $user->id;
    }
}