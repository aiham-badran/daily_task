<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Category;
use App\Models\Category as theCategory;
use App\Models\Task;

class CategoryController extends Controller
{

  /**
   * get all categories in database
   *
   * @return Json has collection of categories
   */
  public function category()
  {
    $allCate = theCategory::all();
    if (count($allCate) > 0) {
      $resources =  Category::collection($allCate->keyBy->id);
      foreach ($allCate as $cate)
        $countTask[$cate->id] = Task::where('categories_id', $cate->id)->count();
      return response()->json(["data" => $resources, "counter" => $countTask]);
    }
    return [];
  }
  /**
   * add new category
   * @param Request
   * @param Category : model
   *
   * @return Json has message and record added
   */
  public function store(Request $request, theCategory $cate)
  {
    $validator = $this->validatorCategory($request);
    if ($validator !== "validator") {
      return response()->json($validator, 422);
    }
    $cate->category_name = $request->category_name;
    $cate->category_description = is_null($request->category_description) ? "" : $request->category_description;
    $cate->category_color = $request->category_color;
    $cate->category_symbol = is_null($request->category_symbol) ? "" : $request->category_symbol;
    $cate->save();
    return response()->json(["message" => "The Category was added successfully", "category" => $cate], 200);
  }
  /**
   * edit category
   * @param Request
   * @param Category : model
   *
   * @return Json has message and record update
   */
  public function update(Request $request, theCategory $cate)
  {
    $validator = $this->validatorCategory($request);
    if ($validator !== "validator") {
      return response()->json($validator, 422);
    }
    $id = $request->id;
    $theCate = $cate::find($id);

    $theCate->category_name = $request->category_name;
    $theCate->category_description = is_null($request->category_description) ? "" : $request->category_description;
    $theCate->category_color = $request->category_color;
    $theCate->category_symbol = is_null($request->category_symbol) ? "" : $request->category_symbol;
    $theCate->save();
    return response()->json(["message" => "The Category was updated successfully", "category" => $theCate], 200);
  }
  /**
   * delete category using id
   *
   * @param Request
   * @return Json has message
   */
  public function destroy(Request $request)
  {
    $id = $request->input("id");
    if (theCategory::destroy($id))
      return response()->json(['message' => "the Category is Deleted"], 200);
    else
      return response()->json(['message' => "the Category is not Deleted"], 422);
  }

  public function validatorCategory($request)
  {
    $validator = Validator::make($request->all(), [
      'category_name' => 'required|max:100|min:3',
      'category_color' => 'required',
    ]);
    if ($validator->fails()) {
      return $validator->errors();
    }
    return "validator";
  }
}