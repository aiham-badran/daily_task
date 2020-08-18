<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Category extends JsonResource
{

  public $preserveKeys = true;
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
      "category_name" => $this->category_name,
      "category_description" => $this->category_description,
      "category_color" => $this->category_color,
      "category_symbol" => $this->category_symbol
    ];
  }
}