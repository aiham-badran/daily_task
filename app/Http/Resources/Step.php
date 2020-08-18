<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Step extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return array
   */
  public function toArray($request)
  {
    return [
      "id" => $this->id,
      "step_text" => $this->step_text,
    ];
  }
}