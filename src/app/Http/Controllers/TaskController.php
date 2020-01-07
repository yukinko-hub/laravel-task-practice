<?php

namespace App\Http\Controllers;

use App\Folder;
use App\Task;
use App\Http\Requests\CreateTask;
use App\Http\Requests\EditTask;
use Illuminate\Http\Request;
// ★ Authクラスをインポートする
use Illuminate\Support\Facades\Auth;


class TaskController extends Controller
{
    public function index(int $id)
    {
      // 全てのフォルダを取得する。
      //$folders = Folder::all();
      // ★ ユーザーのフォルダを取得する
      $folders = Auth::user()->folders()->get();


      // 選ばれたフォルダを取得する。
      $current_folder = Folder::find($id);

      // 選ばれたフォルダに紐づくタスクを取得する。
      // $tasks = Task::where('folder_id',$current_folder->id)->get();
      $tasks = $current_folder->tasks()->get(); // 上記から書き換え

      return  view('tasks/index',[
        'folders' => $folders,
        'current_folder_id' => $current_folder->id,
        'tasks' => $tasks,
      ]);
    }

    /**
    * GET /folders/{id}/tasks/create
    */
    public function showCreateForm(int $id)
    {
      return view('tasks/create',[
          'folder_id' => $id
      ]);
    }

    public function create(int $id, CreateTask $request)
    {
      $current_folder = Folder::find($id);

      $task = new Task();
      $task->title = $request->title;
      $task->due_date = $request->due_date;

      $current_folder->tasks()->save($task);

      return redirect()->route('tasks.index',[
          'id' => $current_folder->id,
      ]);
    }

    /**
    * GET /folders/{id}/tasks/{task_id}/edit
    */
    public function showEditForm(int $id, int $task_id)
    {
      $task = Task::find($task_id);

      return view('tasks/edit',[
          'task' => $task,
      ]);
    }

    public function edit(int $id, int $task_id, EditTask $request)
    {
      // 1 リクエストされた ID でタスクデータを取得(これが編集対象になる)
      $task = Task::find($task_id);

      // 2　編集対象のタスクデータに入力値を詰めて save
      $task->title = $request->title;
      $task->status = $request->status;
      $task->due_date = $request->due_date;
      $task->save();

      // 3　編集対象のタスクが属するタスク一覧画面へリダイレクト
      return redirect()->route('tasks.index', [
          'id' => $task->folder_id,
      ]);
    }
}
