<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Category;
use App\Http\Resources\Step;

class Task extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return array
   */
  public function toArray($request)
  {
    return  [
      "id" => $this->id,
      "task_text" => $this->task_text,
      "task_completed" => $this->task_completed,
      "task_image" => $this->task_image,
      "task_at" => $this->task_at,
      "deleted_at" => $this->deleted_at,
      "category" => new Category($this->category),
      "step" => Step::collection($this->step)->keyBy("id")
    ];
  }
}