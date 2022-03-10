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
	    $candidateData = Candidate::get();
        return view('frontend.index',get_defined_vars());
    }
    
    public function candidates($id)
    {
	    $candidateData = Candidate::findOrFail($id);
        return view('frontend.candidate',get_defined_vars());
    }
}
