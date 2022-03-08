<?php
	
	use App\Http\Controllers\AdminController\Admin\ForgotPasswordController;
	use App\Http\Controllers\AdminController\Admin\LoginController;
	use App\Http\Controllers\AdminController\Admin\ResetPasswordController;
	use App\Http\Controllers\AdminController\AdminController;
	use App\Http\Controllers\AdminController\HomeController;
	use App\Models\CampaignNews;
	use Illuminate\Support\Facades\Auth;
	use Illuminate\Support\Facades\Route;
	
	Auth::routes();
	
	Route::get('/home', [HomeController::class, 'index'])->middleware('auth:admin')->name('home');
	
	Route::get('/admin/home', [HomeController::class , 'index'])->name('admin.home');
	
	Route::prefix('admin')->group(function () {
		
		Route::get('login', [LoginController::class , 'showLoginForm'])->name('admin.login');
		Route::get('/', [LoginController::class , 'showLoginForm'])->name('admin.login');
		Route::post('login', [LoginController::class , 'login'])->name('admin.login.submit');
		Route::get('password/reset', [ForgotPasswordController::class , 'showLinkRequestForm'])->name('admin.password.request');
		Route::post('password/email', [ForgotPasswordController::class , 'sendResetLinkEmail'])->name('admin.password.email');
		Route::get('password/reset/{token}', [ResetPasswordController::class , 'showResetForm'])->name('admin.password.reset');
		Route::post('password/reset', [ResetPasswordController::class , 'reset'])->name('admin.password.update');
		Route::post('logout', [LoginController::class , 'logout'])->name('admin.logout');
		
		Route::group(['middleware'=> ['web','auth:admin']],function(){
			
			// Admins Route
			Route::resources([
				'admins'=>AdminController::class,
				'campaign-news'=>CampaignNews::class,
			]);
			
			Route::controller(AdminController::class)->group(function () {
				Route::get('/profile', 'my_profile');
				Route::post('/profileEdit', 'my_profile_edit');
				Route::get('/profileChangePass', 'change_pass');
				Route::post('/profileChangePass', 'change_pass_update');
				Route::get('/admin_delete/{id}', 'admin_delete');
			});
		});
	});
