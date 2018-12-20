<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    // MassAssignmentException 批量赋值异常时(create)，需要在模型中设置可赋值的字段
    protected $fillable = ['content'];

    // 微博关联用户，一条微博属于一个用户 (用户与微博关系：一对多关系)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
