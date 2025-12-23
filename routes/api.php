<?php

use App\Http\Controllers\Api\LessonQuestionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ComentarioController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\PagoController;
use App\Models\User;

Route::group(['prefix' => 'v1'], function () {
 Route::group(['prefix' => 'auth'], function () {
    Route::post('login', [AuthController::class,'login']); // abierta
    Route::post('register', [AuthController::class,'register']); // abierta
 });

    Route::group(['middleware' => 'auth:api'], function () {
        Route::group(['prefix' => 'auth'], function () {

            Route::get('logout', [AuthController::class,'logout']);
            Route::post('refresh', [AuthController::class,'refresh']);
            Route::get('me', [AuthController::class,'me']);
        });


        Route::apiResource('courses', CourseController::class);



        Route::post(
            'questions/{lesson}',
            [LessonQuestionController::class, 'store']
        );
    });

    Route::group(['prefix' => 'public'], function () {
         Route::get('courses', [CourseController::class,'index']);
         Route::get('courses/{id}', [CourseController::class,'show']);
         Route::get('lessons/{id}/{courseId}', [LessonController::class,'show']);
         Route::apiResource('pagos', PagoController::class);
         Route::get(
            'questions/{lesson}',
            [LessonQuestionController::class, 'show']
        );
    });


});


//listar usuarios





