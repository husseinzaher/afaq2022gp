<?php
	
	use App\Http\Controllers\FrontendController\HomeController;
	
	Route::get('/',[HomeController::class,'index']);
	