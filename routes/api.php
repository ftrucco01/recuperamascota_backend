<?php

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

/* AUTH */
Route::namespace('App\Http\Controllers\Auth')->group(function() {
    Route::prefix('auth')->name('auth.')->group(function() {
        Route::post('token', 'AuthController@token');
        Route::post('password/send-link', 'ResetPasswordController@sendResetLinkEmail');
        Route::middleware('token_reset_password_validate')->post('password/reset', 'ResetPasswordController@resetPassword');
        
        Route::middleware('auth:api')->group(function () {
            Route::post('update-password', 'NewPasswordController');
            Route::get('logout', 'AuthController@logout');
        });
    });
});

/* USERS */
Route::middleware('auth:api')->namespace('App\Http\Controllers\User')->group(function() {
    Route::apiResource('users', 'UserController')->except(['destroy', 'show','store', 'update']);
    Route::delete('users/{user}', 'UserController@destroy')->where('user', '[0-9]+')->middleware('CheckUserPermissions');
    Route::get('users/{user}', 'UserController@show')->where('user', '[0-9]+')->middleware('CheckUserPermissions');

    // Use the CheckUserPermissions middleware only for the store and update methods method
    Route::post('users', 'UserController@store')->middleware('CheckUserPermissions');
    Route::put('users/{user}', 'UserController@update')->middleware('CheckUserPermissions');
    Route::get('users/{user}/image' ,'UserController@getUserImage');
    Route::get('users/roles' ,'UserController@roles')->middleware('CheckUserPermissions');

});

// Rutas API para recursos de mascotas
Route::namespace('App\Http\Controllers\Api')->group(function() {
    Route::post('pets', 'PetController@index');
});

/* UPLOADS */
Route::prefix('uploads')->namespace('App\Http\Controllers\Image')->name('uploads.')->group(function() {
    Route::post('{type}', 'ImageController')->name('uploadFiles');
});

