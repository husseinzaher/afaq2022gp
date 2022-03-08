<?php

use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\UserController;
use \Illuminate\Support\Facades\Auth;
//use App\Http\Controllers\HomeController;
use \App\Http\Controllers\AdminController\Admin\LoginController;
use \App\Http\Controllers\AdminController\Admin\ForgotPasswordController;
use \App\Http\Controllers\AdminController\Admin\ResetPasswordController;
use \App\Http\Controllers\AdminController\HomeController;
use \App\Http\Controllers\AdminController\AdminController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->middleware('auth:admin')->name('home');

Route::get('/admin/home', [HomeController::class , 'index'])->name('admin.home');

Route::prefix('admin')->group(function () {

    Route::get('login', [LoginController::class , 'showLoginForm'])->name('admin.login');
    Route::post('login', [LoginController::class , 'login'])->name('admin.login.submit');
    Route::get('password/reset', [ForgotPasswordController::class , 'showLinkRequestForm'])->name('admin.password.request');
    Route::post('password/email', [ForgotPasswordController::class , 'sendResetLinkEmail'])->name('admin.password.email');
    Route::get('password/reset/{token}', [ResetPasswordController::class , 'showResetForm'])->name('admin.password.reset');
    Route::post('password/reset', [ResetPasswordController::class , 'reset'])->name('admin.password.update');
    Route::post('logout', [LoginController::class , 'logout'])->name('admin.logout');


    Route::group(['middleware'=> ['web','auth:admin']],function(){
            // Admins Route
        Route::resource('admins', '\App\Http\Controllers\AdminController\AdminController' , []);
        Route::controller(AdminController::class)->group(function () {
            Route::get('/profile', 'my_profile');
            Route::post('/profileEdit', 'my_profile_edit');
            Route::get('/profileChangePass', 'change_pass');
            Route::post('/profileChangePass', 'change_pass_update');
            Route::get('/admin_delete/{id}', 'admin_delete');
        });
    });
});
