<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FollowersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // 关注
    public function store(User $user)
    {
        // 虽然正常途径无法自己关注自己，但用postman可以突破这个限制，所以加一层判断：自己关注自己时，跳转首页
        if (Auth::user()->id === $user->id) {
            return redirect('/');
        }

        // 虽然正常途径对于已经关注的用户，无法再次关注，但postman可以突破这个限制，所以加一层判断：没有关注时，才能执行关注
        if (!Auth::user()->isFollowing($user->id)) {
            Auth::user()->follow($user->id);
        }

        return redirect()->route('users.show', $user->id);
    }

    // 取消关注
    public function destroy(User $user)
    {
        // 虽然正常途径无法自己取关自己，但用postman可以突破这个限制，所以加一层判断：自己取关自己时，跳转首页
        if (Auth::user()->id === $user->id) {
            return redirect('/');
        }

        // 虽然正常途径对于已经取关的用户，无法再次取关，但postman可以，所以加一层判断：没有取关时(即已关注时)，才能执行取关
        if (Auth::user()->isFollowing($user->id)) {
            Auth::user()->unfollow($user->id);
        }

        return redirect()->route('users.show', $user->id);
    }
}
