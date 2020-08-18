<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Personal extends Model
{
  //  Determine the correct table in the database

  protected $table = "personal";
  protected $fillable = ["full_name", "phone_number", "job", "image", "userId"];
}