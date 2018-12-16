<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class SessionsController extends Controller
{
    // 使用 Auth 中间件提供的 guest 选项，用于指定一些只允许未登录用户访问的动作
    public function __construct()
    {
        $this->middleware('guest', [    // guest 只允许未登录用户访问的动作
            'only' => ['create']        // 这1个方法，只允许未登录用户访问，其余方法都行要登录访问（即：注册只允许未登录访问）
        ]);
    }

    // 展示登录页面
    public function create()
    {
        return view('sessions.create');
    }

    // 处理登录提交，登录成功，则存储会话（登录）
    public function store(Request $request)
    {
       $credentials = $this->validate($request, [
           'email' => 'required|email|max:255',
           'password' => 'required'
       ]);

       if (Auth::attempt($credentials,$request->has('remember'))) {
            // 登录成功后的相关操作
            session()->flash('success', '欢迎回来！');
            // return redirect()->route('users.show', [Auth::user()]);
            return redirect()->intended(route('users.show', [Auth::user()]));
            // intended 方法，该方法可将页面重定向到上一次请求尝试访问的页面上
            // intended 方法，并接收一个【默认跳转地址参数】，当上一次请求记录为空时，跳转到默认地址上。
        } else {
            // 登录失败后的相关操作
            session()->flash('danger', '很抱歉，您的邮箱和密码不匹配');
            return redirect()->back();
        }
    }

    // 退出登录，销毁会话（登录）
    public function destroy()
    {
        Auth::logout();
        session()->flash('success', '您已成功退出！');
        return redirect('login');
    }
}
