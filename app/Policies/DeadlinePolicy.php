<?php

namespace App\Policies;

use App\Models\Deadline;
use App\Models\User;

class DeadlinePolicy
{
    public function view(User $user, Deadline $deadline)
    {
        return $deadline->user_id === $user->id;
    }

    public function update(User $user, Deadline $deadline)
    {
        return $deadline->user_id === $user->id;
    }

    public function delete(User $user, Deadline $deadline)
    {
        return $deadline->user_id === $user->id;
    }
}