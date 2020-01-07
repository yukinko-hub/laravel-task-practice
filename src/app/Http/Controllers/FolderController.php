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
}
