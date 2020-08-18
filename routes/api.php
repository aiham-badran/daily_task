<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group([
  'middleware' => 'api',
  'namespace' => 'Api'
], function ($router) {
  Route::group(['prefix' => 'auth', 'namespace' => 'Auth'], function () {

    Route::post('login', 'LoginController@login')->name('login');
    Route::get('logout', 'AccountController@logout')->name('logout');

    //profile route
    Route::group(['prefix' => 'profile'], function () {
      Route::get('', 'AccountController@profile')->name('profile');
      Route::put('update', 'AccountController@profileUpdate');
      Route::put('password', 'AccountController@changedPassword');
      Route::post('image', 'AccountController@changedImage');
    });
  });

  //category
  Route::group(['prefix' => 'category'], function () {
    Route::get("", "CategoryController@category");
    Route::post("create", "CategoryController@store");
    Route::put("update", "CategoryController@update");
    Route::delete("deleted", "CategoryController@destroy");
  });

  //task
  Route::group(['prefix' => 'task'], function () {
    Route::get("trash ", "TasksController@getTaskDeleted");
    Route::get("Tasks-count", "TasksController@taskInDate");
    Route::post("tasks", "TasksController@tasks");
    Route::post("create", "TasksController@store");
    Route::put("update", "TasksController@update");
    Route::put("completed", "TasksController@completed");
    Route::put("restore", "TasksController@restore");
    Route::delete("deleted", "TasksController@destroy");
  });

  //step
  Route::group(['prefix' => 'step'], function () {
    Route::post("create", "StepsController@store");
    Route::put("update", "StepsController@update");
    Route::delete("deleted", "StepsController@destroy");
  });

  //setting
  Route::group(['prefix' => 'settings'], function () {
    Route::get('', "SettingsController@setting");
    Route::put('update', "SettingsController@update");
  });
});