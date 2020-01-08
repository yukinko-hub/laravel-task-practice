<?php

namespace App\Http\Controllers;

use App\Folder;
use App\Task;
use App\Http\Requests\CreateTask;
use App\Http\Requests\EditTask;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * タスク一覧
     * @param Folder $folder
     * @return \Illuminate\View\View
     */
    public function index(Folder $folder)
    {
      // ユーザーのフォルダを取得する
      $folders = Auth::user()->folders()->get();

      // 選ばれたフォルダに紐づくタスクを取得する。
      $tasks = $folder->tasks()->get();

      return  view('tasks/index',[
        'folders' => $folders,
        'current_folder_id' => $folder->id,
        'tasks' => $tasks,
      ]);
    }

    /**
     * タスク作成フォーム
     * @param Folder $folder
     * @return \Illuminate\View\View
     */
    public function showCreateForm(Folder $folder)
    {
      return view('tasks/create',[
          'folder_id' => $folder->id
      ]);
    }

    /**
     * タスク作成
     * @param Folder $folder
     * @param CreateTask $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function create(Folder $folder, CreateTask $request)
    {
      $task = new Task();
      $task->title = $request->title;
      $task->due_date = $request->due_date;

      $folder->tasks()->save($task);

      return redirect()->route('tasks.index',[
          'folder' => $folder->id,
      ]);
    }

    /**
     * タスク編集フォーム
     * @param Folder $folder
     * @param Task $task
     * @return \Illuminate\View\View
     */
    public function showEditForm(Folder $folder, Task $task)
    {
      $this->checkRelation($folder, $task);

      return view('tasks/edit',[
          'task' => $task,
      ]);
    }

    /**
     * タスク編集
     * @param Folder $folder
     * @param Task $task
     * @param EditTask $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function edit(Folder $folder, Task $task, EditTask $request)
    {
      $this->checkRelation($folder, $task);

      // 編集対象のタスクデータに入力値を詰めて save
      $task->title = $request->title;
      $task->status = $request->status;
      $task->due_date = $request->due_date;
      $task->save();

      // 編集対象のタスクが属するタスク一覧画面へリダイレクト
      return redirect()->route('tasks.index', [
          'folder' => $task->folder_id,
      ]);
    }

    /**
     * タスク削除フォーム
     * @param Folder $folder
     * @param Task $task
     * @return \Illuminate\View\View
     */
    public function showDeleteForm(Folder $folder, Task $task)
    {
      $this->checkRelation($folder, $task);

      return view('tasks/delete',[
          'task' => $task,
      ]);
    }

    /**
     * タスク削除
     * @param Folder $folder
     * @param Task $task
     * @return \Illuminate\Http\RedirectResponse
     */
    public function remove(Folder $folder, Task $task)
    {
      $this->checkRelation($folder, $task);

      // 削除対象のタスクを削除
      $task->delete();

      // 削除対象のタスクが属するタスク一覧画面へリダイレクト
      return redirect()->route('tasks.index', [
          'folder' => $folder->id,
      ]);
    }

    /**
     * タスク移動フォーム
     * @param Folder $folder
     * @param Task $task
     * @return \Illuminate\View\View
     */
    public function showMoveForm(Folder $folder, Task $task)
    {
      $this->checkRelation($folder, $task);

      // ユーザーのフォルダを取得する
      $folders = Auth::user()->folders()->get();

      // 一つもフォルダを作っていなければタスク一覧画面へリダイレクト
      if (count($folders) <=1 ) {
        return redirect()->route('tasks.index', [
            'folder' => $task->folder_id,
        ]);
      }

      return view('tasks/move',[
          'folder' => $folder,
          'task' => $task,
          'folders' => $folders,
      ]);
    }

    /**
     * タスク移動
     * @param Folder $folder
     * @param Task $task
     * @param Folder $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function exemove(Folder $folder, Task $task, Request $request)
    {
      $this->checkRelation($folder, $task);

      // 移動対象のタスクデータに入力値を詰めて save
      $task->folder_id = $request->input('folder');
      $task->save();

      // 移動対象のタスクが属するタスク一覧画面へリダイレクト
      return redirect()->route('tasks.index', [
          'folder' => $task->folder_id,
      ]);
    }


    private function checkRelation(Folder $folder, Task $task)
    {
        if ($folder->id !== $task->folder_id) {
            abort(404);
        }
    }
}
