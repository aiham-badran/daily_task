<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{

  /**
   *
   * @param Request
   * if user exist
   * @return token : json has access_token and token_type and expires_in (by seconds )
   * else
   * @return error: json has An error message if the user does not exist or there is an error in the entry data
   */
  public function login(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'email' => 'required|email',
      'password' => 'required'
    ]);
    if ($validator->fails()) {
      return response()->json($validator->errors(), 422)->header("access-control-allow-origin", "*");
    }
    $cards = $request->only(['email', 'password']);

    if ($token = Auth::attempt($cards))
      return $this->createNewToken($token);

    return response()->json(['Login' => ['Username or Password is not Correct']], 404);
  }


  protected function createNewToken($token)
  {
    return response()->json([
      'access_token' => $token,
    ], 200);
  }
}