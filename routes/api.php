<?php

use App\Http\Controllers\QuestionController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/* Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum'); */

/* nyilvános végpontok létrehozása
get questions
post questions
 */

Route::get('/questions', [QuestionController::class, 'index']);
Route::post('/question', [QuestionController::class, 'store']);
Route::get('/users', [UserController::class, 'index']);
Route::post('/user', [UserController::class, 'store']);