<form action="{{ route('statuses.store') }}" method="POST">
    @include('shared._errors')
    {{ csrf_field() }}
    <textarea class="form-control" rows="3" palaceholder="聊聊新鲜事吧..." name="content">{{ old('content') }}</textarea>
    <button tyep="submit" class="btn btn-primary pull-right">发布</button>
</form>
