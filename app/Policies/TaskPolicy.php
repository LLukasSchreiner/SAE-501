<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\Project;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TaskPolicy
{
    protected function isProjectMember(User $user, Task $task): bool
    {

        return $task->project->users->contains($user->id);
    }


    public function view(User $user, Task $task): bool
    {

        return $this->isProjectMember($user, $task);
    }

    public function update(User $user, Task $task): bool
    {
        return $this->isProjectMember($user, $task);
    }

    public function delete(User $user, Task $task): bool
    {
        return $this->isProjectMember($user, $task);
    }
}