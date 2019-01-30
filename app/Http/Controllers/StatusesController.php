<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\Status;
use Auth;

class StatusesController extends Controller
{
  // auth中间件，表示只有登录才能访问 StatusesController 控制器中的方法
  public function __construct()
  {
    $this->middleware('auth');
  }

  // 创建微博
  public function store(Request $request)
  {
    $this->validate($request, [
      'content' => 'required|max:140'
    ]);

    Auth::user()->statuses()->create([
      'content' => $request['content']
    ]);

    return redirect()->back();
  }

  // 删除微博：这里我们使用的是『隐性路由模型绑定』功能，Laravel 会自动查找并注入对应 ID 的实例对象 $status
  public function destroy(Status $status)
    {
        $this->authorize('destroy', $status); // 授权删除微博：只有直接才能删除自己的微博
        $status->delete();
        session()->flash('success', '微博已被成功删除！');
        return redirect()->back();
    }
}
