<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\Api\AuthController;
use \App\Http\Controllers\Api\ProfileController;

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
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::prefix('v1')->group(function () {
    Route::middleware(['cors', 'localization'])->group(function () {
        /*user register*/
        Route::controller(AuthController::class)->group(function () {
            Route::post('/register_mobile', 'registerMobile');
            Route::post('/phone_verification', 'register_phone_post');
            Route::post('/resend_code', 'resend_code');
            Route::post('/register', 'register');
            Route::post('/login', 'login');
            Route::post('/forget_password', 'forgetPassword');
            Route::post('/confirm_reset_code', 'confirmResetCode');
            Route::post('/reset_password', 'resetPassword');
            Route::get('/user_data/{id}', 'user_data');
        });
        /*end user register*/
        Route::controller(ProfileController::class)->group(function () {
            Route::get('/terms_and_conditions', 'terms_and_conditions');
            Route::get('/about_us', 'about_us');
            Route::get('/contact_number', 'contact_number');
            Route::get('/banks', 'banks');
            Route::get('/get_user_data/{id}', 'get_user_data');
        });
    });

    Route::group(['middleware' => ['auth:api', 'cors', 'localization']], function () {
        Route::controller(AuthController::class)->group(function () {
            Route::post('/change_password', 'changePassword');
            Route::post('/change_phone_number', 'change_phone_number');
            Route::post('/check_code_change_phone_number', 'check_code_changeNumber');
            Route::post('/edit_account', 'user_edit_account');
            //===============logout========================
            Route::post('/logout', 'logout');

        });
        Route::post('/contact_us', 'Api\ProfileController@contact_us');


        Route::get('/list_notifications', 'Api\ApiController@listNotifications');
        Route::post('/delete_Notifications/{id}', 'Api\ApiController@delete_Notifications');
    });
});

