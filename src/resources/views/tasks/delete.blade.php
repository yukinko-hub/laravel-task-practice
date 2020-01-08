@extends('layout')

@section('styles')
  @include('share.flatpickr.styles')
@endsection

@section('content')
  <div class="container">
    <div class="row">
      <div class="col col-md-offset-3 col-md-6">
        <nav class="panel panel-default">
          <div class="panel-heading">タスクを削除する</div>
          <div class="panel-body">
            <form action="{{ route('tasks.remove', ['folder' => $task->folder_id, 'task' => $task->id]) }}" method="POST">
              @csrf
              <div class="form-group">
                <label for="title">タイトル</label>
                {{ $task->title }}
              </div>
              <div class="form-group">
                <label for="status">状態</label>
                <span class="label {{ $task->status_class}}">{{ $task->status_label }}</span>
              </div>
              <div class="form-group">
                <label for="due_date">期限</label>
                {{ $task->formatted_due_date }}
              </div>
              <div class="text-right">
                <button type="submit" class="btn btn-primary">削除</button>
                <a href="{{ route('tasks.index',['folder' => $task->folder_id]) }}" class="btn btn-secondary">一覧へ戻る</a>
              </div>
            </form>
          </div>
        </nav>
      </div>
    </div>
  </div>
@endsection

@section('scripts')
  @include('share.flatpickr.scripts')
@endsection