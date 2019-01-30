<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\User;
use App\Models\Status;

class StatusPolicy
{
    use HandlesAuthorization;

    // 只能删除自己发的微博，要求：当前用户id（$user->id） 等于 微博用户的id（$status->user_id）
    public function destroy(User $user, Status $status)
    {
        return $user->id === $status->user_id;
    }
}