<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
  // Determine the correct table in the database

  protected $table = "categories";

  public function task()
  {
    return $this->hasMany('App\Models\Task', 'categories_id');
  }
}