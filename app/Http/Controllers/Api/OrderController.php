<?php

namespace App\Http\Controllers\Api;

use App\Admin;
use App\AdminOrder;
use App\Cart;
use App\Http\Resources\CartCollection;
use App\Http\Resources\CartResource;
use App\Http\Resources\OrderCollection;
use App\Http\Resources\OrderResource;
use App\Notification;
use App\Notifications\NewAdminNotification;
use App\Order;
use App\Product;
use App\Provider;
use App\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Validator;

class OrderController extends Controller
{
    public function add_to_cart(Request $request){
        $rules = [
           'product_id'    => 'required|exists:products,id',
           'product_count' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return ApiController::respondWithErrorObject(validateRules($validator->errors(), $rules));
        // create new Order
        $product = Product::find($request->product_id);
        if ($product == null)
        {
            $errors = [
                'message' => ' لا يوجد هذا المنتج'
            ];
            return ApiController::respondWithErrorClient($errors);
        }elseif ($product->less_amount > $request->product_count)
        {
            $errors = [
                'message' => 'أقل كميه لهذا  المنتج هي '  . $product->less_amount,
            ];
            return ApiController::respondWithErrorClient($errors);
        }
        $check_order = Order::whereUserId($request->user()->id)
            ->where('provider_id' , $product->provider_id)
            ->where('product_id' , $product->id)
            ->where('status' , 'on_cart')
            ->first();
        if ($check_order == null)
        {
            $delivery_price = $product->delivery_price == null ? 0 : $product->delivery_price;
            $order = Order::create([
                'provider_id'   => $product->provider_id,
                'user_id'       => $request->user()->id,
                'product_id'    => $request->product_id,
                'order_price'   => $product->price * $request->product_count,
                'delivery_price' => $delivery_price,
                'status'        => 'on_cart',
                'product_count' => $request->product_count,
            ]);
        }else{
            $product_count  = $check_order->product_count + $request->product_count;
            $order_price = $check_order->order_price + ($product->price * $request->product_count);
            $delivery_price = $check_order->delivery_price + ($product->delivery_price == null ? 0 : $product->delivery_price);
            $check_order->update([
                'provider_id'   => $product->provider_id,
                'user_id'       => $request->user()->id,
                'product_id'    => $request->product_id,
                'order_price'   => $order_price,
                'delivery_price' => $delivery_price,
                'status'        => 'on_cart',
                'product_count' => $product_count,
            ]);
            $order = $check_order;
        }

        // check cart
        $check_cart = Cart::whereUserId($request->user()->id)
            ->where('status' , 'opened')
            ->first();
        if ($check_cart)
        {
            $items_price = $check_cart->items_price + $order->order_price;
            $delivery_price = $check_cart->delivery_price + $order->delivery_price;
            $total_price = $items_price + $delivery_price;
            $check_cart->update([
                'items_price'    => $items_price,
                'delivery_price' => $delivery_price,
                'total_price'     => $total_price,
            ]);
            $cart = $check_cart;
        }else{
            // create new Cart
            $items_price = $order->order_price;
            $delivery_price = $order->delivery_price;
            $total_price = $items_price + $delivery_price;
            $cart = Cart::create([
                'user_id'         => $request->user()->id,
                'items_price'    => $items_price,
                'delivery_price' => $delivery_price,
                'total_price'     => $total_price,
                'transfer_photo'  => null,
                'invoice_id'      => null,
                'payment_status'  => 'wait',
                'status'          => 'opened'
            ]);
        }
        $order->update([
            'cart_id'  => $cart->id,
        ]);
        $success = [
            'message' => 'تم أضافه  العنصر الي السله بنجاح',
            'cart_id' => $cart->id,
        ];
        return ApiController::respondWithSuccessData($success);

    }
    public function cart_items(Request $request)
    {
        $cart = Cart::whereUserId($request->user()->id)->orderBy('id' , 'desc')->first();
        if ($cart)
        {
            if ($cart->status != 'opened')
            {
                $success = [
                    'message' => ' السله فارغه',
                ];
                return ApiController::respondWithSuccessData($success);
            }
            $cart_orders = Order::whereCartId($cart->id)->get();
            if ($cart_orders->count() > 0)
            {
                return ApiController::respondWithSuccessData(new CartResource($cart));
            }else{
                $success = [
                    'message' => ' السله فارغه',
                ];
                return ApiController::respondWithSuccessData($success);
            }
        }
        else{
            $errors = [
                'message' => ' لا توجد هذه السله'
            ];
            return ApiController::respondWithErrorClient($errors);
        }
    }
    public function complete_cart_orders(Request $request)
    {
        $rules = [
            'cart_id'    => 'required|exists:carts,id',
            'payment_type' => 'required|in:online,bank_transfer',
            'transfer_photo' => 'required_if:payment_type,bank_transfer|mimes:jpg,jpeg,png,gif,tif,psd,bmp|max:5000',
            'charge_id'      => 'required_if:payment_type,online|in:2,6,11',
            'delivery_date'  => 'required|date',
            'delivery_time'  => 'required',
            'delivery_latitude' => 'required',
            'delivery_longitude' => 'required',
            'delivery_address' => 'required|string',
            'more_details'    => 'sometimes|string',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return ApiController::respondWithErrorObject(validateRules($validator->errors(), $rules));

        $cart = Cart::find($request->cart_id);
        if ($cart)
        {
            if ($cart->status != 'opened')
            {
                $success = [
                    'message' => ' السله فارغه',
                ];
                return ApiController::respondWithErrorClient($success);
            }
            if ($cart->orders->count() > 0)
            {
                /**
                 *  bank transfer
                */
                if ($request->payment_type == 'bank_transfer')
                {

                    $cart->update([
                        'status'  => 'new_no_paid',
                        'payment_status'  => 'done',
                        'payment_type' => 'bank_transfer',
                        'transfer_photo' => $request->file('transfer_photo') == null ? null : UploadImage($request->file('transfer_photo') , 'photo' , '/uploads/transfers'),
                        'delivery_date'  => $request->delivery_date,
                        'delivery_time'  => $request->delivery_time,
                        'delivery_latitude' => $request->delivery_latitude,
                        'delivery_longitude' => $request->delivery_longitude,
                        'delivery_address' => $request->delivery_address == null ? null : $request->delivery_address,
                        'more_details' => $request->more_details == null ? null : $request->more_details,
                    ]);
                    // update orders status
                    foreach ($cart->orders as $order)
                    {
                        $order->update([
                            'status'  => 'new_no_paid',
                            'delivery_date'  => $request->delivery_date,
                            'delivery_time'  => $request->delivery_time,
                            'delivery_latitude' => $request->delivery_latitude,
                            'delivery_longitude' => $request->delivery_longitude,
                            'delivery_address' => $request->delivery_address == null ? null : $request->delivery_address,
                            'more_details' => $request->more_details == null ? null : $request->more_details,
                        ]);
                        $provider = Provider::find($order->provider_id);
                        $provider->notify(new NewAdminNotification($order->id));
                    }

                    $success = [
                        'message' => 'تم أرسال طلبك بنجاح الي الأدراه',
                    ];
                    return ApiController::respondWithSuccessData($success);
                }
                elseif ($request->payment_type == 'online'){
                    // Online Payment
                    $amount = $cart->total_price;
                    $charge = $request->charge_id;
                    $user = $request->user();
                    $token = "rLtt6JWvbUHDDhsZnfpAhpYk4dxYDQkbcPTyGaKp2TYqQgG7FGZ5Th_WD53Oq8Ebz6A53njUoo1w3pjU1D4vs_ZMqFiz_j0urb_BH9Oq9VZoKFoJEDAbRZepGcQanImyYrry7Kt6MnMdgfG5jn4HngWoRdKduNNyP4kzcp3mRv7x00ahkm9LAK7ZRieg7k1PDAnBIOG3EyVSJ5kK4WLMvYr7sCwHbHcu4A5WwelxYK0GMJy37bNAarSJDFQsJ2ZvJjvMDmfWwDVFEVe_5tOomfVNt6bOg9mexbGjMrnHBnKnZR1vQbBtQieDlQepzTZMuQrSuKn-t5XZM7V6fCW7oP-uXGX-sMOajeX65JOf6XVpk29DP6ro8WTAflCDANC193yof8-f5_EYY-3hXhJj7RBXmizDpneEQDSaSz5sFk0sV5qPcARJ9zGG73vuGFyenjPPmtDtXtpx35A-BVcOSBYVIWe9kndG3nclfefjKEuZ3m4jL9Gg1h2JBvmXSMYiZtp9MR5I6pvbvylU_PP5xJFSjVTIz7IQSjcVGO41npnwIxRXNRxFOdIUHn0tjQ-7LwvEcTXyPsHXcMD8WtgBh-wxR8aKX7WPSsT1O8d8reb2aR7K3rkV3K82K_0OgawImEpwSvp9MNKynEAJQS6ZHe_J_l77652xwPNxMRTMASk1ZsJL";
                    $data = "{\"PaymentMethodId\":\"$charge\",\"CustomerName\": \"$user->name\",\"DisplayCurrencyIso\": \"SAR\",
                    \"MobileCountryCode\":\"+966\",\"CustomerMobile\": \"$user->phone_number\",
                    \"CustomerEmail\": \"customer@email.com\",\"InvoiceValue\": $amount,\"CallBackUrl\": \"https://0ccasion.station.tqnee.com/check-status\",
                    \"ErrorUrl\": \"https://0ccasion.station.tqnee.com/error-status\",\"Language\": \"ar\",\"CustomerReference\" :\"ref 1\",
                    \"CustomerCivilId\":12345678,\"UserDefinedField\": \"Custom field\",\"ExpireDate\": \"\",
                    \"CustomerAddress\" :{\"Block\":\"\",\"Street\":\"\",\"HouseBuildingNo\":\"\",\"Address\":\"\",\"AddressInstructions\":\"\"},
                    \"InvoiceItems\": [{\"ItemName\": \"$user->name\",\"Quantity\": 1,\"UnitPrice\": $amount}]}";
                    $fatooraRes = MyFatoorah($token, $data);
                    $result = json_decode($fatooraRes);
                    if ($result->IsSuccess === true) {
                        $cart->update([
                            'invoice_id'  => $result->Data->InvoiceId,
                            'delivery_date'  => $request->delivery_date,
                            'delivery_time'  => $request->delivery_time,
                            'delivery_latitude' => $request->delivery_latitude,
                            'delivery_longitude' => $request->delivery_longitude,
                            'delivery_address' => $request->delivery_address == null ? null : $request->delivery_address,
                            'more_details' => $request->more_details == null ? null : $request->more_details,
                        ]);
                        $success = [
                            'payment_url' => $result->Data->PaymentURL,
                        ];
                        return ApiController::respondWithSuccessData($success);

                    }
                }
            }else{
                $errors = [
                    'message' => ' السله فارغه',
                ];
                return ApiController::respondWithErrorClient($errors);
            }
        }else{
            $errors = [
                'message' => ' لا توجد هذه السله'
            ];
            return ApiController::respondWithErrorClient($errors);
        }
    }

    public function fatooraStatus()
    {
        $token = "rLtt6JWvbUHDDhsZnfpAhpYk4dxYDQkbcPTyGaKp2TYqQgG7FGZ5Th_WD53Oq8Ebz6A53njUoo1w3pjU1D4vs_ZMqFiz_j0urb_BH9Oq9VZoKFoJEDAbRZepGcQanImyYrry7Kt6MnMdgfG5jn4HngWoRdKduNNyP4kzcp3mRv7x00ahkm9LAK7ZRieg7k1PDAnBIOG3EyVSJ5kK4WLMvYr7sCwHbHcu4A5WwelxYK0GMJy37bNAarSJDFQsJ2ZvJjvMDmfWwDVFEVe_5tOomfVNt6bOg9mexbGjMrnHBnKnZR1vQbBtQieDlQepzTZMuQrSuKn-t5XZM7V6fCW7oP-uXGX-sMOajeX65JOf6XVpk29DP6ro8WTAflCDANC193yof8-f5_EYY-3hXhJj7RBXmizDpneEQDSaSz5sFk0sV5qPcARJ9zGG73vuGFyenjPPmtDtXtpx35A-BVcOSBYVIWe9kndG3nclfefjKEuZ3m4jL9Gg1h2JBvmXSMYiZtp9MR5I6pvbvylU_PP5xJFSjVTIz7IQSjcVGO41npnwIxRXNRxFOdIUHn0tjQ-7LwvEcTXyPsHXcMD8WtgBh-wxR8aKX7WPSsT1O8d8reb2aR7K3rkV3K82K_0OgawImEpwSvp9MNKynEAJQS6ZHe_J_l77652xwPNxMRTMASk1ZsJL";
        $PaymentId = \Request::query('paymentId');
        $resData = MyFatoorahStatus($token, $PaymentId);
        $result = json_decode($resData);
        if ($result->IsSuccess === true && $result->Data->InvoiceStatus === "Paid") {
            $InvoiceId = $result->Data->InvoiceId;
            // get booking
            $cart = Cart::where('invoice_id' , $InvoiceId)->first();
            if ($cart)
            {
                $cart->update([
                    'status'  => 'new_paid',
                    'payment_status'  => 'done',
                    'payment_type' => 'online',
                    'invoice_id'  => null,
                ]);
                // update orders status
                if ($cart->orders->count() > 0)
                {
                    foreach ($cart->orders as $order)
                    {
                        $order->update([
                            'status'  => 'new_paid',
                            'delivery_date'  => $cart->delivery_date,
                            'delivery_time'  => $cart->delivery_time,
                            'delivery_latitude' => $cart->delivery_latitude,
                            'delivery_longitude' => $cart->delivery_longitude,
                            'delivery_address' => $cart->delivery_address == null ? null : $cart->delivery_address,
                            'more_details' => $cart->more_details == null ? null : $cart->more_details,
                        ]);
                        // send notification to provider
                        $provider = Provider::find($order->provider_id);
                        $provider->notify(new NewAdminNotification($order->id));
                        if($order->product->delivery == 'yes')
                        {
                            $note = $order->delivery_date . ' ' . $order->delivery_time;
                            $obj = array(
                                'sender_data' => array(
                                    'address_type' => "business",
                                    'name' => $order->provider->name,
                                    'email' => $order->provider->email,
                                    'apartment' => "",
                                    'building' => "",
                                    'street' => $order->provider->address,
                                    'landmark' => "",
                                    'city' => array(
                                        'code' => $order->provider->city->code,
                                        'lat' => $order->provider->latitude,
                                        'lon' => $order->provider->longitude,
                                    ),
                                    'country' => array(
                                        'id' => 191
                                    ),
                                    'phone' => $order->provider->phone_number,
                                ),
                                'recipient_data' => array(
                                    'address_type' => "business",
                                    'name' => $order->user->name,
                                    'email' => $order->user->email == null ? "recipient@example.com" : $order->user->email,
                                    'apartment' => "",
                                    'building' => "",
                                    'street' => $order->delivery_address,
                                    'landmark' => "",
                                    'city' => array(
                                        'id' => "26148057",
                                        'lat' => $cart->delivery_latitude,
                                        'lon' => $cart->delivery_longitude,
                                    ),
                                    'country' => array(
                                        'id' => 191
                                    ),
                                    'phone' => $order->user->phone_number,
                                ),
                                'dimensions' => array(
                                    "weight" => 1,
                                    "width" => 10,
                                    "length" => 10,
                                    "height" => 10,
                                    "unit" => "METRIC",
                                    "domestic" => false
                                ),
                                'package_type' => array(
                                    "courier_type" => "B_2_B"
                                ),
                                'charge_items' => array(
                                    array(
                                        "charge_type" => "cod",
                                        "charge" => Setting::find(1)->delivery_price,
                                        "payer" => "recipient"
                                    ),
//            array(
//                "charge_type" => "service_custom",
//                "charge" => 0,
//                "payer" => "recipient"
//            )
                                ),
                                "recipient_not_available" => "do_not_deliver",
                                "payment_type" => "credit_balance",
                                "payer" => "recipient",
                                "parcel_value" => 145,
                                "fragile" => true,
                                "note" => $note,
                                "piece_count" => "",
                                "force_create" => true,
                                "reference_id" => $order->id,
                            );

                            createColdtOrder($obj);
                        }
                    }
                }
                // send notification to admin
                $admin = Admin::where('admin_category_id' , 5)->inRandomOrder()->first();
                $admin->notify(new NewAdminNotification($cart->id));

                // create new Admin Order
                AdminOrder::create([
                    'admin_id'  => $admin->id,
                    'order_id'  => $cart->id,
                ]);
                return redirect()->route('fatoora-success');
            }else{
                $errors = [
                    'message' => 'حدث خطأ ما',
                ];
                return ApiController::respondWithErrorClient($errors);
            }
        }
    }
    public function errorStatus()
    {
        flash(' حدث خطأ ما حاول في وقت لاحق')->error();
        return redirect()->back();
    }

    public function orders(Request  $request)
    {
        $user = $request->user();
        $rules = [
            'status'  => 'sometimes|in:new_paid,new_no_paid,works_on,completed,canceled,all',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return ApiController::respondWithErrorObject(validateRules($validator->errors(), $rules));
        if ($request->status == 'all')
        {
            $carts = Cart::whereNotIn('status', ['opened', 'sent', 'on_cart'])
                ->where('user_id' , $user->id)
                ->orderBy('id' , 'desc')
                ->paginate(10);
        }
        elseif ($request->status != null)
        {
            $carts = Cart::whereUserId($user->id)
                ->where('status' , $request->status)
                ->orderBy('id' , 'desc')
                ->paginate(10);
        }else{
            $carts = Cart::whereNotIn('status', ['opened', 'sent', 'on_cart'])
                ->where('user_id' , $user->id)
                ->orderBy('id' , 'desc')
                ->paginate(10);
        }
        if ($carts->count() > 0)
        {
            return ApiController::respondWithSuccessData(new CartCollection($carts));
        }else{
            $errors = [
                'message' => 'لا يوجد طلبات'
            ];
            return ApiController::respondWithErrorClient($errors);
        }
    }
    public function order_details($id)
    {
        $order = Cart::find($id);
        if ($order)
        {
            return ApiController::respondWithSuccessData(new CartResource($order));
        }else{
            $errors = [
                'message' => 'هذا الطلب غير موجود'
            ];
            return ApiController::respondWithErrorClient($errors);
        }
    }

    public function delete_from_cart(Request $request)
    {
        $rules = [
            'cart_id'     => 'required|exists:carts,id',
            'order_id'    => 'required|exists:orders,id',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return ApiController::respondWithErrorObject(validateRules($validator->errors(), $rules));

        $cart = Cart::find($request->cart_id);
        if ($cart)
        {
            if ($cart->status == 'sent')
            {
                $errors = [
                    'message' => ' السله فارغه',
                ];
                return ApiController::respondWithErrorClient($errors);
            }
            if ($cart->orders->count() > 0)
            {
                $order = Order::find($request->order_id);
                $cart->update([
                    'items_price'    => $cart->items_price - $order->order_price,
                    'delivery_price' => $cart->delivery_price - $order->delivery_price,
                    'total_price'     => $cart->total_price - ($order->order_price + $order->delivery_price),
                ]);
                $order->delete();
                $success = [
                    'message' => 'تم حذف العنصر من السله بنجاح',
                ];
                return ApiController::respondWithSuccessData($success);
            }else{
                $errors = [
                    'message' => ' السله فارغه',
                ];
                return ApiController::respondWithErrorClient($errors);
            }
        }else{
            $errors = [
                'message' => ' السله فارغه',
            ];
            return ApiController::respondWithErrorClient($errors);
        }

    }
}
