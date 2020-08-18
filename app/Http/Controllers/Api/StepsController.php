<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Step;

class StepsController extends Controller
{
  /**
   * store step in database
   *
   * @param Request
   * @param Step
   *
   * @return Json
   */
  public function store(Request $request, Step $step)
  {
    $validator = Validator::make($request->all(), [
      'step_text' => 'required',
      'tasks_id' => 'required'
    ]);
    if ($validator->fails()) {
      return response()->json($validator->errors(), 422);
    }
    $step->step_text = $request->step_text;
    $step->tasks_id = $request->tasks_id;
    $step->save();
    return response()->json(["message" => "The Step was created successfully", "step" => $step]);
  }

  /**
   * set and update step in database
   *
   * @param Request
   * @param Step
   *
   * @return Json
   */
  public function update(Request $request, Step $step)
  {
    return $request->all();
    $validator = Validator::make($request->all(), [
      'step_text' => 'required',
    ]);
    if ($validator->fails()) {
      return response()->json($validator->errors(), 422);
    }
    $step = $step->where("id", $request->id)->first();
    $step->step_text = $request->step_text;
    $step->save();
    return response()->json(["message" => "The Step was created successfully", "step" => $step]);
  }

  /**
   * destroy step
   * @param Request
   * @return Json
   */
  public function destroy(Request $request)
  {
    if (Step::destroy($request->id))
      return response()->json(["message" => "The Step was delete successfully"]);
    return response()->json(["message" => "The Step was not delete ", $request], 422);
  }
}