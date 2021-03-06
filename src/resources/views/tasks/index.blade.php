@extends('layout')

@section('content')
    <div class="container">
      <div class="row">
        <div class="col col-md-4">
          <nav class="panel panel-default">
            <div class="panel-heading">フォルダ</div>
            <div class="panel-body">
              <a href="{{ route('folders.create') }}" class="btn btn-default btn-block">
                フォルダを追加する
              </a>
              <p></p>
              <a href="{{ route('folders.edit',['folder' => $current_folder_id]) }}" class="btn btn-default card-link">
                編集
              </a>
              <a href="{{ route('folders.delete',['folder' => $current_folder_id]) }}" class="btn btn-default card-link">
                削除
              </a>
            </div>
            <div class="list-group">
              @foreach($folders as $folder)
              <a href="{{ route('tasks.index', ['folder' => $folder->id]) }}" class="list-group-item {{ $current_folder_id === $folder->id ? 'active' : ''}}">
                {{ $folder->title }}
              </a>
              @endforeach
            </div>
          </nav>
        </div>
        <div class="column col-md-8">
          <!-- ここにタスクが表示される -->
          <div class="panel panel-default">
            <div class="panel-heading">タスク</div>
            <div class="panel-body">
              <div class="text-right">
                <a href="{{ route('tasks.create',['folder' => $current_folder_id]) }}" class="btn btn-default btn-block">
                  タスクを追加する
                </a>
              </div>
            </div>
            <table class="table">
              <thead>
                <tr>
                  <th>タイトル</th>
                  <th>状態</th>
                  <th>期限</th>
                  <th></th>
                  <th></th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                @foreach($tasks as $task)
                <tr>
                  <td>{{ $task->title }}</td>
                  <td>
                    <span class="label {{ $task->status_class}}">{{ $task->status_label }}</span>
                  </td>
                  <td>{{ $task->formatted_due_date }}</td>
                  <td width="50px"><a href="{{ route('tasks.edit', ['folder' => $task->folder_id, 'task' => $task->id]) }}">編集</a></td>
                  <td width="50px"><a href="{{ route('tasks.move', ['folder' => $task->folder_id, 'task' => $task->id]) }}">移動</a></td>
                  <td width="50px"><a href="{{ route('tasks.delete', ['folder' => $task->folder_id, 'task' => $task->id]) }}">削除</a></td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
@endsection
