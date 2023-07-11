<?php

namespace App\Policies;

use App\Models\TaskList;
use App\Models\User;

class TaskListPolicy
{
    public function create(User $user, TaskList $list): bool
    {
        return $user->id === $list->owner_id;
    }

    public function update(User $user, TaskList $list): bool
    {
        return $user->id === $list->owner_id || $user->hasPermissionTo('update');
    }

    public function delete(User $user, TaskList $list): bool
    {
        return $user->id === $list->owner_id || $user->hasPermissionTo('delete');
    }
}
