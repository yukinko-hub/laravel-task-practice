<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Folder extends Model
{
    public function tasks()
    {
      return $this->hasMany('App\Task');

      /* 以下を省略して記載
      * return $this->hasMany('App\Task','folder_id','id');
      */
    }
}
