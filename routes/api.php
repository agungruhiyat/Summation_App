<?php
use App\Http\Controllers\SummationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::group(["prefix"=>"summations"],function(){
    Route::post("/",[SummationController::class,"store"]);
    Route::get("/",[SummationController::class,"index"]);
    Route::put("/{id}",[SummationController::class,"update"]);
    Route::delete("/{id}",[SummationController::class,"delete"]);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
