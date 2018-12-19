<li id="status-{{ $status->id }}">
  <a href="{{ route('users.show', $user->id )}}">
    <img src="{{ $user->gravatar() }}" alt="{{ $user->name }}" class="gravatar"/>
  </a>
  <span class="user">
    <a href="{{ route('users.show', $user->id )}}">{{ $user->name }}</a>
  </span>
  <span class="timestamp">
    {{ $status->created_at->diffForHumans() }}
  </span>
  <span class="content">{{ $status->content }}</span>
  <!-- can 对 StatusPolicy 中的授权方法 auth_destroy 进行判断：只有自己能删除自己的微博，有权限，才显示删除按钮 -->
  @can('auth_destroy', $status)
    <form action="{{ route('statuses.destroy', $status->id) }}" method="POST">
      {{ csrf_field() }}
      {{ method_field('DELETE') }}
      <button type="submit" class="btn btn-sm btn-danger status-delete-btn">删除</button>
    </form>
  @endcan
</li>