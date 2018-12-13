<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UsersController extends Controller
{
  public function create()
  {
    return view('users.create');
  }

  public function show(User $user) {
    return view('users.show', compact('user'));
  }

  public function store(Request $request) {
    $this->validate($request, [
      'name'      => 'required|max:50',
      'email'     => 'required|email|unique:users|max:255', // unique:users,其中users是表名
      'password'  => 'required|confirmed|min:6'
    ]);

    $user = User::create([  // $user 是User::create()创建成功后返回的一个用户对象
      'name'      => $request->name,
      'email'     => $request->email,
      'password'  => bcrypt($request->password),
    ]);
    session()->flash('success', '欢迎，您将在这里开启一段新的旅程~');
    return redirect()->route('users.show',[$user]);
    // route()方法会自动获取模型的主键，其等价于：redirect()->route('users.show',[$user->id]);
  }
}
