<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LoginController as Login;
use App\Http\Controllers\Api\TaskController as Task;

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


/*
|--------------------------------------------------------------------------
| API Routes LOGIN - USERS
|--------------------------------------------------------------------------
|
*/
Route::post('login', [
    App\Http\Controllers\Api\LoginController::class,
    'login'
]);

Route::apiResource('resp', Login::class)
  ->only(['index','show'])
  ->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/*
|--------------------------------------------------------------------------
| API Routes Tareas
|--------------------------------------------------------------------------
|
*/

Route::apiResource('task', Task::class)
  ->only(['index','show','store','update'])
  ->middleware('auth:sanctum');

Route::apiResource('task/show', Task::class)
  ->only(['show'])
  ->middleware('auth:sanctum');

Route::post('task/getByTitle', [
    App\Http\Controllers\Api\TaskController::class,
    'getByTitle'
])->middleware('auth:sanctum');

Route::post('task/getByDescription', [
    App\Http\Controllers\Api\TaskController::class,
    'getByDescription'
])->middleware('auth:sanctum');

Route::get('getStatus', [
  App\Http\Controllers\Api\TaskController::class,
  'getStatus'
])->middleware('auth:sanctum');

Route::post('task/updateStatus', [
    App\Http\Controllers\Api\TaskController::class,
    'updateStatus'
])->middleware('auth:sanctum');

Route::delete('task/delete/{task}',[Task::class, 'delete'])->middleware('auth:sanctum')->middleware('permission:delete_task');
