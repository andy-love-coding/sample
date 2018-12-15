<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UsersController extends Controller
{
  // 显示创建用户的页面
  public function create()
  {
    return view('users.create');
  }

  // 显示用户信息
  public function show(User $user) {
    return view('users.show', compact('user'));
  }

  // 处理创建用户的post提交，存现用户信息到数据库
  public function store(Request $request) {
    $this->validate($request, [
      'name'      => 'required|max:50',
      'email'     => 'required|email|unique:users|max:255', // unique:users,其中users是表名
      'password'  => 'required|confirmed|min:6'
    ]);

    // 验证通过后，存现用户注册信息到数据库
    $user = User::create([  // $user 是User::create()创建成功后返回的一个用户对象
      'name'      => $request->name,
      'email'     => $request->email,
      'password'  => bcrypt($request->password),
    ]);

    Auth::login($user);  // 注册成功后，自动登录
    session()->flash('success', '欢迎，您将在这里开启一段新的旅程~');
    return redirect()->route('users.show',[$user]);
    // route()方法会自动获取模型的主键，其等价于：redirect()->route('users.show',[$user->id]);
  }
}
