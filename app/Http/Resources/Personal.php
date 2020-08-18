<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;


class Personal extends JsonResource
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
      "fullName" => $this->full_name,
      "phone" => $this->phone_number,
      "job" => $this->job,
      "image" => $this->image
    ];
  }
}