<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class JWTAuthenticate extends BaseMiddleware
{
  /**
   * Handle an incoming request.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \Closure  $next
   * @return mixed
   */
  public function handle($request, Closure $next)
  {
    $expired = false;
    if (!$token = $this->auth->setRequest($request)->getToken()) {
      return response()->json(['message' => "Token not provided", 'status' => "401-1"], 401);
    }

    try {
      $this->auth->authenticate($token);
    } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $th) {
      $expired = true;
    } catch (\Tymon\JWTAuth\Exceptions\TokenBlacklistedException $th) {
      return response()->json(['message' => $th->getMessage(), 'status' => "401-0"], 401);
    }

    if ($expired) {
      try {
        $newToken = $this->auth->setRequest($request)->parseToken()->refresh();
        return response()->json(['message' => "Token has expired", "status" => "401-3", "new_token" => $newToken], 401);
      } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $th) {
        return response()->json(['message' => "Token Expired ", 'status' => "401-2"], 401);
      } catch (\Tymon\JWTAuth\Exceptions\JWTException $th) {
        return response()->json(['message' => $th->getMessage(), 'status' => "401-0"], 401);
      }
    }

    return $next($request);
  }
}