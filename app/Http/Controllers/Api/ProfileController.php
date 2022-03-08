<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\NotificationCollection;
use App\UserNotification;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use JWTAuth;
use Validator;
use App\User;
use App;
use Auth;

class ProfileController extends Controller
{
    //

    public function about_us()
    {
        $about = App\AboutUs::first();
        $all = [
            'title' => $about->title,
            'content' => $about->content,
        ];
        return ApiController::respondWithSuccess($all);
    }

    public function contact_number()
    {
        $number = App\Setting::first();
        $all = [
            'contact_number' => $number->contact_number,
        ];
        return ApiController::respondWithSuccess($all);
    }

    public function terms_and_conditions()
    {
        $terms = App\TermsCondition::first();
        $all = [
            'title' => $terms->title,
            'content' => $terms->content,
        ];
        return ApiController::respondWithSuccess($all);
    }

    public function banks()
    {
        $banks = App\Bank::all();
        if ($banks->count() > 0) {
            return ApiController::respondWithSuccessData(App\Http\Resources\BankResource::collection($banks));
        }
    }

    public function contact_us(Request $request)
    {
        $rules = [
            'message' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())
            return ApiController::respondWithErrorArray(validateRules($validator->errors(), $rules));

        // create new contact
        $contact = App\ContactUs::create([
            'user_id' => $request->user()->id,
            'name' => $request->user()->name,
            'phone_number' => $request->user()->phone_number,
            'message' => $request->message,
        ]);
        $success = [
            'message' => 'تم أرسال  الرساله الي  الأدراه بنجاح'
        ];
        return ApiController::respondWithSuccess($success);
    }

    public function store_device_token(Request $request)
    {
        $rules = [
            'device_token' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())
            return ApiController::respondWithErrorArray(validateRules($validator->errors(), $rules));
        $created = ApiController::createDeviceToken($request->device_token);

        $success = [
            'device_token' => $created->device_token
        ];
        return ApiController::respondWithSuccess($success);
    }

    public function get_visitors_notifications($id)
    {
        $notifications = UserNotification::Where('device_token', $id)
            ->orderBy('id','desc')
            ->get();
        if ($notifications->count() > 0)
        {
            return ApiController::respondWithSuccessData(new NotificationCollection($notifications));
        }else{
            $errors = [
                'message'  => trans('messages.no_notifications')
            ];
            return ApiController::respondWithErrorArray(array($errors));
        }

    }

}
