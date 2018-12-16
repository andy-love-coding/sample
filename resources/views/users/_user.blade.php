<li>
    <img src="{{ $user->gravatar() }}" alt="{{ $user->name }}" class="gravatar"/>
    <a href="{{ route('users.show', $user->id )}}" class="username">{{ $user->name }}</a>
    <!-- can 用来在 blade 模板中做授权判断：只有登录用户是管理员，且操作的对象不是自己，用户列表中的用户后面才显示删除按钮 -->
    @can('destroy_others', $user)
      <form action="{{ route('users.destroy', $user->id) }}" method="post">
        {{ csrf_field() }}
        {{ method_field('DELETE') }}
        <button type="submit" class="btn btn-sm btn-danger delete-btn">删除</button>
      </form>
    @endcan
</li>