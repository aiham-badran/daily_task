<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use App\Http\Resources\Setting as ResSetting;

class SettingsController extends Controller
{
  public function setting()
  {
    return ResSetting::collection(Setting::all()->keyBy->setting_Key);
  }

  public function update(Request $request)
  {
    $key = $request->key;
    $user_id  = $request->user_id;
    $value = $request->value;

    $setting = Setting::where("setting_key", $key)->first();
    $setting->setting_value = $value;
    $setting->save();
    return response()->json([$setting]);
  }
}