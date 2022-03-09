<?php
	
	namespace App\Models;
	
	use Illuminate\Database\Eloquent\Model;
	use Spatie\MediaLibrary\HasMedia;
	use Spatie\MediaLibrary\InteractsWithMedia;
	
	class CampaignNews extends Model implements HasMedia
	{
		use  InteractsWithMedia;
		
		protected $appends = ['image'];
		protected $guarded = [];
	}
