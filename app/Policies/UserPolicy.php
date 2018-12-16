<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    // 定义 self_update 授权方法，用来实现：用户只能编辑自己的资料    
    // self_update 的第一个参数 $currentUser 为当前登录用户，第二个参数为 需要操作的对象（或用户）。
    public function self_update(User $currentUser, User $user)
    {
        return $currentUser->id === $user->id;  // 若需要授权操作的用户的用户就是当前用户，返回true，否则返回false
    }
    // 授权方法 self_update 的使用步骤：
    // 1：在 app/Providers/AuthServiceProvider.php 中指定 模型与策略的对应关系 
    // 2：在控制器的方法中，通过 authorize 方法验证授权策略（该方法第一个参数为 授权方法 的名称，第二个为需要授权的数据（或用户））
    //   2.1：在 UserController 中的edit、update方法中调用：authorize('self_update',$user)，这里的 $user 对应 self_update 方法中的第二个参数，调用 self_update 时，默认情况下，不需要 传递第一个参数，也就是当前登录用户至该方法内，因为框架会自动加载当前登录用户。
    

    public function destroy_others(User $currentUser, User $user)
    {
        // 只有当前用户拥有管理员权限且删除的用户不是自己时才显示删除按钮，才有权删除
        return $currentUser->is_admin && $currentUser->id !== $user->id;
    }
}
