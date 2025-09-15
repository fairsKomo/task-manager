<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\Task;
use App\Models\User;

class TaskPolicy
{
    public function view(User $user, Task $task): bool
    {
        
        return $task->project->user_id === $user->id;
    }

    public function update(User $user, Task $task): bool
    {
        return $task->project->user_id === $user->id;
    }
}
