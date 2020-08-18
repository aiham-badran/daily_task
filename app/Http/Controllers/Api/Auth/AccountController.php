<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\User as user;
use App\Models\Personal;
use App\Http\Resources\Personal as personally;
use App\User as users;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class AccountController extends Controller
{
  /**
   * Returns all data for the current user
   * @return Resources User
   */
  public function profile()
  {
    return new user(auth()->user());
  }

  /**
   *  Personal data modification
   *
   * @return Json : Update message
   */
  public function profileUpdate(Request $request)
  {

    Personal::where("userId", $request->input("id"))->update([
      "full_name" => is_null($request->input("personal")["fullName"]) ? "" : $request->input("personal")["fullName"],
      "job" => is_null($request->input("personal")["job"]) ? "" : $request->input("personal")["job"],
      "phone_number" => is_null($request->input("personal")["phone"]) ? "" : $request->input("personal")["phone"],
      "image" => is_null($request->input("personal")["image"]) ? "" : $request->input("personal")["image"],
    ]);

    return response()->json(['message' => 'Successfully Update Profile'], 200);
  }

  /**
   * change the password
   *
   * @return Json : Messages
   */
  public function changedPassword(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'oldPassword' => 'required',
      'newPassword' => 'required'
    ]);
    if ($validator->fails()) {
      return response()->json($validator->errors(), 422);
    }
    $user = users::find($request->input("id"));

    if (!Hash::check($request->input("oldPassword"), $user->password)) {
      return response()->json(['Message' => "The password is incorrect", "status" => 400], 404);
    }

    $user->password = $request->input("newPassword");
    $user->save();
    return response()->json(['message' => "The password is changed", "status" => 200]);
  }

  /**
   *
   *
   */
  public function changedImage(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
    ]);
    if ($validator->fails()) {
      return response()->json($validator->errors(), 422);
    }

    $theExtension =  $request->file('image')->extension();
    $user = users::find($request->input("id"));

    $currentImage = $request->input("currentImage");
    if (Storage::url($currentImage))
      Storage::delete([$currentImage]);
    $ImageName =  $user->user_name . "_" . $user->id . "_" . uniqid() . "." . $theExtension;
    $urlImag =  $request->file('image')->storeAs('profile', $ImageName);

    Personal::where("userId", $request->input("id"))->update([
      "image" => is_null($urlImag) ? "" : $urlImag,
    ]);
    return response()->json(['message' => "Image is Changed", "imageUrl" =>  $urlImag, "status" => 200]);
  }

  /**
   * Delete the token when logout
   * @return Json : logout message
   */
  public function logout()
  {
    auth()->logout(true);
    return response()->json(['message' => 'Successfully logged out'])->header('Access-Control-Allow-Origin', '*');
  }
}