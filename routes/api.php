<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\LessonController;
use App\Models\User;

Route::group(['prefix' => 'v1'], function () {
 Route::group(['prefix' => 'auth'], function () {
    Route::post('login', [AuthController::class,'login']); // abierta
 });

    Route::group(['middleware' => 'auth:api'], function () {
        Route::group(['prefix' => 'auth'], function () {

            Route::get('logout', [AuthController::class,'logout']);
            Route::post('refresh', [AuthController::class,'refresh']);
            Route::get('me', [AuthController::class,'me']);
        });


        Route::apiResource('courses', CourseController::class);
    });

    Route::group(['prefix' => 'public'], function () {
         Route::get('courses', [CourseController::class,'index']);
         Route::get('courses/{id}', [CourseController::class,'show']);
         Route::get('lessons/{id}/{courseId}', [LessonController::class,'show']);
         Route::get('video/{id}/stream', [LessonController::class, 'stream'])
    ->name('video.stream');

    });


});


//listar usuarios





