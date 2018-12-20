<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
// 引入消息通知类：ResetPassword
use App\Notifications\ResetPassword;
use Auth;

class User extends Authenticatable
{
    use Notifiable;
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    // 监听 creating 事件，在用户实例创建之前，先创建用户激活令牌
    public static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            $user->activation_token = str_random(30);
        });
    }

    // 根据用户邮箱，生产头像地址
    public function gravatar($size = '100')
    {
        $hash = md5(strtolower(trim($this->attributes['email'])));
        return "http://www.gravatar.com/avatar/$hash?s=$size";
    }

    // 发送密码重置通知(邮件模板)
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }

    // 用户关联微博，一个用户拥有多条微博：通过用户查找其发布的微博
    public function statuses()
    {
        return $this->hasMany(Status::class);
    }
    
    // 定义模型方法 feed 实现：获取用户的微博，按创建时间倒序排列
    public function feed()
    {
        $user_ids = Auth::user()->followings->pluck('id')->toArray();
        array_push($user_ids,Auth::user()->id);
        return Status::whereIn('user_id', $user_ids) // 查询指定 user_id 的微博
                    ->with('user') // 用 with 预加载微博关联的用户数据 (就是在查询微博的同时，一次性把微博的作者一起查询出来)
                    ->orderBy('created_at','desc');
    }

    // 粉丝列表：通过 某博主(关注人) 来获取他的粉丝列表
    public function followers()
    {
        return $this->belongsToMany(User::Class, 'followers', 'user_id', 'follower_id');
    }

    // 博主列表：通过 某粉丝 来获取他关注的博主(关注人)列表
    public function followings()
    {
        return $this->belongsToMany(User::Class, 'followers', 'follower_id', 'user_id');
    }

    // 关注：粉丝 关注 博主
    public function follow($user_ids)
    {
        if (!is_array($user_ids)) {            // 如果不是数组
            $user_ids = compact('user_ids');   // 则变成关联数组
        }
        $this->followings()->sync($user_ids, false); // 为这个粉丝，添加 博主(关注人) 数组
    }

    // 取消关注：粉丝 取消关注 博主
    public function unfollow($user_ids)
    {
        if (!is_array($user_ids)) {
            $user_ids = compact('user_ids');
        }
        $this->followings()->detach($user_ids); // 为这个粉丝，删除 博主(关注人) 数组
    }

    // 是否关注了：某”粉丝“是否关注了”某博主“，即粉丝的关注人列表中，是否有这个博主(关注人)
    public function isFollowing($user_id)
    {
        return $this->followings->contains($user_id);
    }
}
