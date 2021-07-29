<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\JobController;
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


Route::get('/rest/user/list', [UserController::class, 'list']);
Route::post('/rest/user/add', [UserController::class, 'add']);
Route::post('/rest/user/modify', [UserController::class, 'modify']);

Route::get('/rest/job/list/{id}', [JobController::class, 'list']);
Route::post('/rest/job/add', [JobController::class, 'add']);




