<?php

namespace App\Http\Controllers\Api;

use App\Category;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\HomeResource;
use App\Http\Resources\ProductCollection;
use App\Http\Resources\ProductResource;
use App\Http\Resources\ProviderCollection;
use App\Http\Resources\ProviderResource;
use App\Http\Resources\SliderResource;
use App\Product;
use App\Provider;
use App\Setting;
use App\Slider;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;

class ProductController extends Controller
{
    public function products(Request $request)
    {
        $rules = [
            'provider_id' => 'required|exists:providers,id',
            'activity' => 'sometimes|in:rent,sale',
            'search' => 'sometimes',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return ApiController::respondWithErrorObject(validateRules($validator->errors(), $rules));

        if ($request->search != null && $request->activity == null) {
            $products = Product::whereProviderId($request->provider_id)
                ->where('name', 'LIKE', "%{$request->search}%")
                ->paginate(10);
        } elseif ($request->search == null && $request->activity != null) {
            $products = Product::whereProviderId($request->provider_id)
                ->where('activity', $request->activity)
                ->paginate(10);
        } elseif ($request->search != null && $request->activity != null) {
            $products = Product::whereProviderId($request->provider_id)
                ->where('activity', $request->activity)
                ->where('name', 'LIKE', "%{$request->search}%")
                ->paginate(10);
        } else {
            $products = Product::whereProviderId($request->provider_id)
                ->paginate(10);
        }
        if ($products->count() > 0) {
            return ApiController::respondWithSuccessData(new ProductCollection($products));
        } else {
            $errors = [
                'message' => ' لا يوجد منتجات'
            ];
            return ApiController::respondWithErrorClient($errors);
        }
    }

    public function providers_products_search(Request $request)
    {
        $rules = [
            'key_search' => 'sometimes|string|max:191',
            'activity' => 'sometimes|in:rent,sale',
            'google_city_id' => 'required'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return ApiController::respondWithErrorObject(validateRules($validator->errors(), $rules));

//        $range = Setting::find(1)->search_range;
//        $lat = $request->latitude;
//        $lon = $request->longitude;
        if ($request->key_search != null && $request->activity == null) {
            $products = Product::with('provider')
                ->whereHas('provider', function ($q) use ($request) {
                    $q->with('city');
                    $q->whereHas('city' , function ($d) use ($request){
                        $d->where('google_city_id', $request->google_city_id);
                    });
                })
                ->where('name', 'LIKE', "%{$request->key_search}%")
                ->paginate(10);
            $providers = Provider::with('city')
                ->whereHas('city' , function ($q) use ($request){
                    $q->where('google_city_id', $request->google_city_id);
                })
                ->where('name', 'LIKE', "%{$request->key_search}%")
                ->paginate(10);
        } elseif ($request->key_search == null && $request->activity != null) {
            $products = Product::with('provider')
                ->whereHas('provider', function ($q) use ($request) {
                    $q->with('city');
                    $q->whereHas('city' , function ($d) use ($request){
                        $d->where('google_city_id', $request->google_city_id);
                    });
                })
                ->where('activity', $request->activity)
                ->paginate(10);
            $providers = Provider::with('city')
                ->whereHas('city' , function ($q) use ($request){
                    $q->where('google_city_id', $request->google_city_id);
                })
                ->where('activity', $request->activity)
                ->paginate(10);
        } elseif ($request->key_search != null && $request->activity != null) {
            $products = Product::with('provider')
                ->whereHas('provider', function ($q) use ($request) {
                    $q->with('city');
                    $q->whereHas('city' , function ($d) use ($request){
                        $d->where('google_city_id', $request->google_city_id);
                    });
                })
                ->where('activity', $request->activity)
                ->where('name', 'LIKE', "%{$request->key_search}%")
                ->paginate(15);
            $providers = Provider::with('city')
                ->whereHas('city' , function ($q) use ($request){
                    $q->where('google_city_id', $request->google_city_id);
                })
                ->where('activity', $request->activity)
                ->where('name', 'LIKE', "%{$request->key_search}%")
                ->paginate(10);
        } else {
            $products = Product::with('provider')
                ->whereHas('provider', function ($q) use ($request) {
                    $q->with('city');
                    $q->whereHas('city' , function ($d) use ($request){
                        $d->where('google_city_id', $request->google_city_id);
                    });
                })->paginate(10);
            $providers = Provider::with('city')
                ->whereHas('city' , function ($q) use ($request){
                    $q->where('google_city_id', $request->google_city_id);
                })
                ->paginate(10);
        }
        if ($products->count() > 0 || $providers->count() > 0) {
            $data = [
                'providers' => new ProviderCollection($providers),
                'products' => new ProductCollection($products)
            ];
            return ApiController::respondWithSuccessData($data);
        } else {
            $errors = [
                'message' => ' لا يوجد منتجات ومزودين'
            ];
            return ApiController::respondWithErrorClient($errors);
        }
    }

    public function home_screen(Request $request)
    {
        $rules = [
            'google_city_id' => 'required'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return ApiController::respondWithErrorObject(validateRules($validator->errors(), $rules));

//        $range = Setting::find(1)->search_range;
//        $lat = $request->latitude;
//        $lon = $request->longitude;
        $products = Product::with('provider')
            ->whereHas('provider', function ($q) use ($request) {
                $q->with('city');
                $q->whereHas('city', function ($d) use ($request) {
                    $d->where('google_city_id', $request->google_city_id);
                });
            })->take(10)->get();
        if ($products->count() > 0) {
            return ApiController::respondWithSuccessData(new HomeResource($products));
        } else {
            $errors = [
                'message' => 'المنطقه التي تتواجد بها غير مدعومه'
            ];
            return ApiController::respondWithErrorClient($errors);
        }
    }

    public function product_details($id)
    {
        $product = Product::find($id);
        if ($product) {
            $products = Product::with('provider')
                ->whereHas('provider', function ($q) use ($product) {
                    $q->where('category_id', $product->provider->category_id);
                })
                ->where('id' , '!=' , $id)
                ->get()->take(10);
            $data = [
                'product_details' => new ProductResource($product),
                'similar_products' => ProductResource::collection($products)
            ];
            return ApiController::respondWithSuccessData($data);
        } else {
            $errors = [
                'message' => ' لا يوجد هذا المنتج'
            ];
            return ApiController::respondWithErrorClient($errors);
        }
    }
}
