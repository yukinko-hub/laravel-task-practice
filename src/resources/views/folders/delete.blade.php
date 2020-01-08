@extends('layout')

@section('content')
      <div class="container">
        <div class="row">
          <div class="col col-md-offset-3 col-md-6">
            <nav class="panel panel-default">
              <div class="panel-heading">フォルダをタスクごと削除する</div>
              <div class="panel-body">
                <form action="{{ route('folders.remove', ['folder' => $folder]) }}" method="post">
                  @csrf
                  <div class="form-group">
                    <label for="title">フォルダ名</label>
                    {{ $folder->title }}
                  </div>
                  <div class="text-right">
                    <button type="submit" class="btn btn-primary">送信</button>
                    <a href="{{ route('home') }}" class="btn btn-secondary">一覧へ戻る</a>
                  </div>
                </form>
              </div>
            </nav>
          </div>
        </div>
      </div>
@endsection