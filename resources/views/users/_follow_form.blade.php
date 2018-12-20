<!-- 用户id不是当前登录用户id时，才显示 关注表单；即在别人名下时显示，自己名下不显示  -->
@if($user->id !== Auth::user()->id)
<div id="follow_form">
    <!-- 如果当前登录用户(粉丝) 是否关注了要进行操作的这个用户($user->id) -->
    @if(Auth::user()->isFollowing($user->id))
        <form action="{{ route('followers.destroy', $user->id) }}" method="post">
            {{ csrf_field() }}
            {{ method_field('DELETE') }}
            <button type="submit" class="btn btn-sm">取消关注</button>
        </form>
    @else
        <form action="{{ route('followers.store', $user->id) }}" method="post">
            {{ csrf_field() }}
            <button type="submit" class="btn btn-sm btn-primary">关注</button>
        </form>
    @endif
</div>
@endif