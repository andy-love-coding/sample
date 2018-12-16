<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Auth;

class UsersController extends Controller
{
  public function __construct(){
    $this->middleware('auth', [     // auth 只允许已登录用户可以访问的动作
      // except表示，除了这几个方法不需要登录外，其余方法都只允许已登录用户访问
      'except' => ['show','create','store','index']   
    ]);

    $this->middleware('guest', [  // guest 只允许未登录用户访问的动作
        'only' => ['create']      // 这1个方法，只允许未登录用户访问，其余方法都行要登录访问（即：注册只允许未登录访问）
    ]);
  }

  // 展示用户列表
  public function index(){
    $users = User::paginate(10);
    return view('users.index',compact('users'));
  }

  // 展示创建用户的页面
  public function create()
  {
    return view('users.create');
  }

  // 显示用户信息
  public function show(User $user) {  // $user 参数是自动解析路由中的用户 id 对应的用户实例对象
    return view('users.show', compact('user'));
  }

  // 创建用户，保存用户信息到数据库
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

  // 展示用户修改的页面
  public function edit(User $user) {
    $this->authorize('self_update', $user);  // authorize 调用 self_update 策略方法实现：用户只能打开自己的编辑页面
    return view('users.edit', compact('user'));
  }
  
  // 更新用户修改
  public function update(User $user, Request $request) {  // $user 参数是自动解析路由中的用户 id 对应的用户实例对象
    $this->validate($request, [
        'name' => 'required|max:50',
        'password' => 'nullable|confirmed|min:6'
    ]);

    $this->authorize('self_update', $user); // authorize 调用 self_update 策略方法实现：用户只能更新自己的资料

    $data = [];
    $data['name'] = $request->name;
    if($request->password){
        $data['password'] = bcrypt($request->password);
    }
    $user->update($data);

    session()->flash('success','个人资料更新成功');

    return redirect()->route('users.show',$user->id);
  }

  // 删除用户
  public function destroy(User $user)
  {
    // 只有当前用户拥有管理员权限且删除的用户不是自己时，才有权删除(因为在视图中自己后面没有显示删除按钮，其实也无法删除)
    // $user表示要删除的用户,策略类中，destroy_others 策略方法中的第二个参数，第一个参数是当前登录用户，调用策略方法时默认不传
    $this->authorize('destroy_others', $user); 
    $user->delete();
    session()->flash('success', '成功删除用户！');
    return back(); // 将用户重定向到上一次进行删除操作的页面，即用户列表页。
  }
}
