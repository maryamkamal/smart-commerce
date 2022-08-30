<?php

namespace Modules\Checkout\Http\Controllers;

use Exception;
use Modules\Order\Entities\Order;
use Modules\Payment\Facades\Gateway;
use Modules\Checkout\Events\OrderPlaced;
use Modules\Checkout\Services\OrderService;
use DB;
class CheckoutCompleteController
{
    /**
     * Store a newly created resource in storage.
     *
     * @param int $orderId
     * @param \Modules\Checkout\Services\OrderService $orderService
     * @return \Illuminate\Http\Response
     */
    public function store($orderId, OrderService $orderService)
    {
        $order = Order::findOrFail($orderId);

        $gateway = Gateway::get(request('paymentMethod'));

        try {
            $response = $gateway->complete($order);
        } catch (Exception $e) {
            $orderService->delete($order);

            return response()->json([
                'message' => $e->getMessage(),
            ], 403);
        }

        $order->storeTransaction($response);

        event(new OrderPlaced($order));

        session()->put('placed_order', $order);
		
		 /*********** send notification to admin app with new order message*******/
		 
		$SERVER_API_KEY = "AAAAOUGahjc:APA91bHBRJjbqgnTKm-AtWUgm_D6y_WJFY2nkmOyh9KyJ-J9cNxfjyLyuCVVr8MarmRz1JPBz-7PnOnCMMl0yIN6p5Zc4MSWhV8O8X-TLhIWccY1FXfCMFJGy7pJ2RwUuHc7HlnHZWdd";
     
	  $mobile_tokens=User::select('mobile_token')->where('mobile_token','!=',null)->get();
	  foreach($mobile_tokens as $mobile_token){
		$tokens[]=$mobile_token->mobile_token;
	    }
        $data = [

            "registration_ids" =>$tokens ,

            "data" => [

                "title" => "طلب جديد",
                "body" => "يوجد طلب جديد فى الأنتظار", 
                "image" => null, 

            ]

        ];

        $dataString = json_encode($data);

        $headers = [

            'Authorization: key=' . $SERVER_API_KEY,

            'Content-Type: application/json',

        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');

        curl_setopt($ch, CURLOPT_POST, true);

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

            
        $response = curl_exec($ch);
		

        if (! request()->ajax()) {
            return redirect()->route('checkout.complete.show');
        }
		
		
       
		
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $order = session('placed_order');

        if (is_null($order)) {
            return redirect()->route('home');
        }

        return view('public.checkout.complete.show', compact('order'));
    }
}
