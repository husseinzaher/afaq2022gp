<?php

namespace App\Http\Controllers\Api;

use App\Category;
use App\City;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\CityResource;
use App\Http\Resources\ProviderCollection;
use App\Http\Resources\SubCategoryResource;
use App\Provider;
use App\Setting;
use App\SubCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;

class CategoryController extends Controller
{
    /**
     * get main categories
     * @categories
     */
    public function categories()
    {
        $categories = Category::all();
        if ($categories->count() > 0) {
            return ApiController::respondWithSuccessData(CategoryResource::collection($categories));
        } else {
            $errors = [
                'message' => 'لا يوجد أقسام'
            ];
            return ApiController::respondWithErrorClient($errors);
        }
    }

    public function cities()
    {
        $cities = City::all();
        if ($cities->count() > 0) {
            return ApiController::respondWithSuccessData(CityResource::collection($cities));
        } else {
            $errors = [
                'message' => 'لا يوجد مدن'
            ];
            return ApiController::respondWithErrorClient($errors);
        }
    }

    public function sub_categories($id)
    {
        $sub_categories = SubCategory::whereCategoryId($id)->get();
        if ($sub_categories->count() > 0) {
            return ApiController::respondWithSuccessData(SubCategoryResource::collection($sub_categories));
        } else {
            $errors = [
                'message' => ' لا يوجد أقسام فرعية'
            ];
            return ApiController::respondWithErrorClient($errors);
        }
    }

    public function providers(Request $request)
    {
        $rules = [
            'category_id' => 'required|exists:categories,id',
            'sub_category_id' => 'sometimes|exists:sub_categories,id',
            'search' => 'sometimes',
            'google_city_id' => 'required'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return ApiController::respondWithErrorObject(validateRules($validator->errors(), $rules));

//        $range = Setting::find(1)->search_range;
//        $lat = $request->latitude;
//        $lon = $request->longitude;
        if ($request->sub_category_id != null && $request->search != null) {
            $providers = Provider::with('provider_categories' , 'city')
                ->whereHas('provider_categories', function ($q) use ($request) {
                    $q->where('sub_category_id', $request->sub_category_id);
                })
                ->whereHas('city', function ($q) use ($request) {
                    $q->where('google_city_id', $request->google_city_id);
                })
                ->where('name', 'LIKE', "%{$request->search}%")
                ->where('category_id', $request->category_id)
                ->paginate(10);
        } elseif ($request->sub_category_id == null && $request->search != null) {
            $providers = Provider::with('city')
                ->whereHas('city', function ($q) use ($request) {
                    $q->where('google_city_id', $request->google_city_id);
                })
                ->where('name', 'LIKE', "%{$request->search}%")
                ->where('category_id', $request->category_id)
                ->paginate(10);
        } elseif ($request->sub_category_id != null && $request->search == null) {
            $providers = Provider::with('provider_categories', 'city')
                ->whereHas('city', function ($q) use ($request) {
                    $q->where('google_city_id', $request->google_city_id);
                })
                ->whereHas('provider_categories', function ($q) use ($request) {
                    $q->where('sub_category_id', $request->sub_category_id);
                })
                ->where('category_id', $request->category_id)
                ->paginate(10);
        } else {
            $providers = Provider::with('city')
                ->whereHas('city', function ($q) use ($request) {
                    $q->where('google_city_id', $request->google_city_id);
                })
                ->where('category_id', $request->category_id)
                ->paginate(10);

        }
        if ($providers->count() > 0) {
            return ApiController::respondWithSuccessData(new ProviderCollection($providers));
        } else {
            $errors = [
                'message' => ' لا يوجد مزودين خدمات'
            ];
            return ApiController::respondWithErrorClient($errors);
        }
    }
}
