<?php
	
	namespace App\Http\Controllers\AdminController\CampaignNews;
	
	use App\Http\Controllers\Controller;
	use App\Models\CampaignNews;
	use Illuminate\Http\Request;
	
	class CampaignNewsController extends Controller
	{
		/**
		 * Display a listing of the resource.
		 *
		 * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
		 */
		public function index()
		{
			$data = CampaignNews::get();
			
			return view('admin.campaign-news.index', get_defined_vars());
		}
		
		/**
		 * Show the form for creating a new resource.
		 *
		 * @return \Illuminate\Http\Response
		 */
		public function create()
		{
			return view('admin.campaign-news.create');
		}
		
		/**
		 * Store a newly created resource in storage.
		 *
		 * @param  \Illuminate\Http\Request  $request
		 * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Routing\Redirector
		 */
		public function store(Request $request)
		{
			$this->validate($request, [
				'title'       => 'required',
				'description' => 'required',
				'photo'       => 'image',
			]);
			$data = CampaignNews::create([
				'title'       => $request['title'],
				'description' => $request['description'],
			]);
			if ($data) {
				// Update Feature Services
				$data->clearMediaCollection('photo');
				$data->addMedia($request['photo'])->toMediaCollection('photo');
			}
			
			return redirect(route('admin.candidates.index'));
		}
		
		/**
		 * Display the specified resource.
		 *
		 * @param  \App\Models\CampaignNews  $campaignNews
		 * @return \Illuminate\Http\Response
		 */
		public function show(CampaignNews $campaignNews)
		{
			//
		}
		
		/**
		 * Show the form for editing the specified resource.
		 *
		 * @param  \App\Models\CampaignNews  $campaignNews
		 * @return \Illuminate\Http\Response
		 */
		public function edit(CampaignNews $campaignNews)
		{
			//
		}
		
		/**
		 * Update the specified resource in storage.
		 *
		 * @param  \Illuminate\Http\Request  $request
		 * @param  \App\Models\CampaignNews  $campaignNews
		 * @return \Illuminate\Http\Response
		 */
		public function update(Request $request, CampaignNews $campaignNews)
		{
			//
		}
		
		/**
		 * Remove the specified resource from storage.
		 *
		 * @param  \App\Models\CampaignNews  $campaignNews
		 * @return \Illuminate\Http\Response
		 */
		public function delete($id)
		{
			$data = CampaignNews::findOrFail($id);
			$data->delete();
			return back();
		}
	}
