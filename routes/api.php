<?php

use App\Http\Controllers\WeihnachtsAPIController;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get("/", [WeihnachtsAPIController::class, "index"]);
Route::get("/user", [WeihnachtsAPIController::class, "user"]);
Route::get("/targets", [WeihnachtsAPIController::class, "targets"]);
Route::get("/{id}/wishes", [WeihnachtsAPIController::class, "wishes"]);
Route::post("/addwish", [WeihnachtsAPIController::class, "addWish"]);
Route::post("/deletewish", [WeihnachtsAPIController::class, "deleteWish"]);
Route::post("/claimwish", [WeihnachtsAPIController::class, "claimWish"]);
Route::get("/{id}/gifters", [WeihnachtsAPIController::class, "gifters"]);
Route::get("/{id}/info", [WeihnachtsAPIController::class, "userinfo"]);

