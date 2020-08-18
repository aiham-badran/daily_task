<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
  use SoftDeletes;
  /**
   *
   */
  public function category()
  {
    return $this->belongsTo('App\Models\Category', 'categories_id');
  }

  /**
   *
   */
  public function step()
  {
    return $this->hasMany("App\Models\Step", "tasks_id");
  }
}