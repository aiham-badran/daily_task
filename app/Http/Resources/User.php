<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Personal as personally;
use App\Http\Resources\Personal;

class User extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return array
   */
  public function toArray($request)
  {
    $p_data = new Personal(personally::where("userId", $this->id)->first());
    return [
      "id" => $this->id,
      "username" => $this->user_name,
      "email" => $this->email,
      "personal" => $p_data,
    ];
  }
}