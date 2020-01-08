<?php

namespace App\Http\Controllers;

use App\Folder;
use Illuminate\Http\Request;
use App\Http\Requests\CreateFolder; // 追加
use Illuminate\Support\Facades\Auth;// 追加

class FolderController extends Controller
{
    public function showCreateForm()
    {
      return view('folders/create');
    }

    public function create(CreateFolder $request) // 引数の型を変更
    {
      // フォルダモデルのインスタンスを作成する
      $folder = new Folder();

      // タイトルに入力値を代入する
      $folder->title = $request->title;

      // ★ ユーザーに紐づけて保存
      Auth::user()->folders()->save($folder);

      // インスタンスの状態をデータベースに書き込む
      //$folder->save();

      return redirect()->route('tasks.index',[
          'folder' => $folder->id,
      ]);

    }
    
    /**
     * フォルダ編集フォーム
     * @param Folder $folder
     * @return \Illuminate\View\View
     */
    public function showEditForm(Folder $folder)
    {
      return view('folders/edit',[
          'folder' => $folder,
      ]);
    }

    /**
     * フォルダ編集
     * @param Folder $folder
     * @param CreateFolder $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function edit(Folder $folder, CreateFolder $request)
    {
      // 編集対象のフォルダデータに入力値を詰めて save
      $folder->title = $request->title;
      $folder->save();

      // 一覧画面へリダイレクト
      return redirect()->route('tasks.index', [
          'folder' => $folder->id,
      ]);
    }

    /**
     * フォルダ削除フォーム
     * @param Folder $folder
     * @return \Illuminate\View\View
     */
    public function showDeleteForm(Folder $folder)
    {
      //$this->checkRelation($folder, $task);

      return view('folders/delete',[
          'folder' => $folder,
      ]);
    }

    /**
     * フォルダ削除
     * @param Folder $folder
     * @return \Illuminate\Http\RedirectResponse
     */
    public function remove(Folder $folder)
    {
      //削除対象のフォルダに属するタスクを全削除
      $folder->tasks()->delete();

      // 削除対象のタスクを削除
      $folder->delete();

      // ログインユーザーに紐づくフォルダを一つ取得する
      $folder_first = Auth::user()->folders()->first();

      // 一つもフォルダを作っていなければホームページをレスポンスする
      if (is_null($folder_first)) {
        return view('home');
      }

      // フォルダがあればそのフォルダのタスク一覧にリダイレクトする
      return redirect()->route('tasks.index', [
          'folder' => $folder_first->id,
      ]);
    }
}
