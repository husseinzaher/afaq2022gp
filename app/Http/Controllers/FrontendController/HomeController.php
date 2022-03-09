<?php

namespace App\Http\Controllers\FrontendController;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
use Illuminate\Http\Request;

class HomeController extends Controller
{
	
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
    	
        return view('frontend.index');
    }
    
    public function candidates($id)
    {
    	$data = Candidate::findOrFail($id);
        return view('frontend.candidate',get_defined_vars());
    }
}
