<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\Api;
use App\Http\Controllers\Api\catagoryController;
use App\Http\Controllers\api\subCatagoryController;
use App\Http\Controllers\api\userController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();

});



Route::get('/test',function (){
        return "hello";
    })->middleware('auth:sanctum');


//Authentaction

Route::post('/auth/register', [Api\AuthController::class, 'createUser']);
Route::post('/auth/login', [Api\AuthController::class, 'loginUser']);
// user 
Route::get('/users',[userController::class,'index']);

//catagories
Route::get('/catatgory',[catagoryController::class,'index']);
Route::post('/catatgory',[catagoryController::class,'store']);
Route::get('/catatgory/{id}',[catagoryController::class,'show']);
Route::put('/catatgory/{id}',[catagoryController::class,'edit']);
Route::post('/catatgory/{id}',[catagoryController::class,'update']);
Route::delete('/catatgory/{id}',[catagoryController::class,'destroy']);
//subCatagories
Route::get('/sub_catatgory',[subCatagoryController::class,'index']);
Route::post('/sub_catatgory',[subCatagoryController::class,'store']);
Route::get('/sub_catatgory/{id}',[subCatagoryController::class,'show']);
Route::put('/sub_catatgory/{id}',[subCatagoryController::class,'edit']);
Route::post('/sub_catatgory/{id}',[subCatagoryController::class,'update']);
Route::delete('/sub_catatgory/{id}',[subCatagoryController::class,'destroy']);



