<?php
namespace Themes\Storefront\Http\Controllers\Api;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Modules\User\Entities\User;
use Modules\Order\Entities\Order;
use Modules\Order\Entities\OrderProduct;
use Illuminate\Support\Facades\Validator;
use Modules\Currency\Entities\CurrencyRate;
use Illuminate\Support\Facades\Mail;
use Modules\Order\Mail\OrderStatusChanged;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use Modules\Media\Entities\File;
use Modules\FlashSale\Entities\FlashSaleProduct;
use Modules\Transaction\Entities\Transaction;
use DB;
require base_path().'/PHPMailer/src/PHPMailer.php';
require  base_path().'/PHPMailer/src/SMTP.php';


class OrderController
{
    public function orders()
    {
    $orders = Order::orderBy('id', 'desc')->get();
     return \response()->json($orders);	
    }
    
    public function products($id)
    {
    $products = Order::with('products')->find($id);
     return \response()->json($products);	
    }
    
     public function statusUpdate($id)
    {
    $order = Order::find($id);
	//dd($order->mobile_token);
     $order->update(['status' =>request()->get('status')]);
      $message = trans('order::messages.status_updated');
     if (setting('order_status_email')) {
           
		$body =new OrderStatusChanged($order);
		//dd($body);
		$mail = new PHPMailer(true);

                try {
                    //Server settings
                    // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
                    $mail->isSMTP();                                            // Send using SMTP
                    $mail->Host       = env('MAIL_HOST');                    // Set the SMTP server to send through
                    $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
                    $mail->Username   = env('MAIL_USERNAME');                     // SMTP username
                    $mail->Password   = env('MAIL_PASSWORD');                               // SMTP password
                    $mail->SMTPSecure = env('MAIL_ENCRYPTION');         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
                    $mail->Port       = env('MAIL_PORT');                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

                    //Recipients
                    $mail->setFrom(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'));
                    $mail->addAddress($order->customer_email);     // Add a recipient

                    // Add attachments

                    // Content
                    $mail->isHTML(true);
                    $mail->Subject = $message;
                    $mail->Body    = $body->text." ". trans('order::statuses.'.request()->get('status'));
                    $mail->send();
                } catch (Exception $e) {
                    die($e->getMessage());
                }
        
		/*************** send notification with order status *******************************/
		if($order->mobile_token != null){
		//$logo = File::findOrNew(setting('storefront_header_logo'))->path;
        $SERVER_API_KEY = "AAAA0ZxJBvk:APA91bEqBxW4Ni3YpNHoZkJq2DTKt42jOj993C-nlymN0uKFBFf-162bnJPHlsA6dv3RunlJO08ZWXPh4Tr_ZqdwbJvsQzKMoWEyjVAsBnD-qch2xlDI5iEsmW3zpfngAzOYcXf8Ek3-";
   
        $data = [

            "registration_ids" =>[$order->mobile_token] ,

            "data" => [

                "title" => $message,
                "body" => $body->text." ". trans('order::statuses.'.request()->get('status')), 
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
		}
	}

     return \response()->json(['message' => 'updated_succeffly']);	
    }
	
    public function store()
    {
		
		 $order = new Order;
		
		$userToken = request()->get('api_token');
     
                $user = User::where('api_token',$userToken)->first();
		        $order['customer_id'] = $user->id;
                $order['customer_email'] =$user->email;
                $order['customer_phone'] = request()->get('customer_phone');
                $order['customer_first_name'] = $user->first_name;
                $order['customer_last_name'] = $user->last_name;
                $order['billing_first_name'] = $user->first_name;
                $order['billing_last_name'] = $user->last_name;
                $order['billing_address_1'] = request()->get('billing_address_1');
	            $order['billing_city'] = request()->get('billing_city');
		        $order['billing_state'] = request()->get('billing_state');
	         	$order['billing_zip'] = request()->get('billing_zip');
	        	$order['shipping_first_name'] = $user->first_name;
	        	$order['shipping_last_name'] = $user->last_name;
	        	$order['shipping_address_1'] = request()->get('shipping_address_1');
	        	$order['shipping_city'] = request()->get('shipping_city');
	        	$order['shipping_state'] = request()->get('shipping_state');
	    	    $order['shipping_zip'] = request()->get('shipping_zip');
		        $order['sub_total'] = request()->get('sub_total');
		        $order['shipping_method'] = "local_pickup";
		        $order['shipping_cost'] = request()->get('shipping_cost');
		        $order['coupon_id'] = request()->get('coupon_id');
		        $order['discount'] = request()->get('discount');
		        $order['total'] = request()->get('total');
		        $order['payment_method'] =  request()->get('payment_method');
		        $order['currency'] = currency();
		        $order['currency_rate'] = CurrencyRate::for(currency());
		        $order['locale'] = request()->get('locale');
		        $order['status'] = Order::PENDING_PAYMENT;
		        $order['mobile_token'] = request()->get('mobile_token');
                $order->save();
				
		$order_id = Order::max('id');
		
	/*************** save payment with card transaction data ************************/
	if(request()->get('payment_method') == 'card'){
	$transaction = new Transaction;
	$transaction['order_id'] = $order_id;
	$transaction['transaction_id']=request()->get('transaction_id');
	$transaction['payment_method']=request()->get('payment_method');
	$transaction->save();
	}
	/*******save order products in OrderProduct table******/
      
	  $products=[request()->get('products_ids'),request()->get('products_price'),request()->get('products_qty'),request()->get('products_total')];
	  $productsCount= count($products[0]);
	
	  $counter = 0;
	  while ( $counter <$productsCount ) {
		$order_product=OrderProduct::create([
	   'order_id'   =>$order_id,
	   'product_id' =>$products[0][$counter],
	   'unit_price' =>$products[1][$counter],
	   'qty'        =>$products[2][$counter],
	   'line_total' =>$products[3][$counter],
	   ]);
	   /************ flashsale order products ***************************/
	   $flash_product = FlashSaleProduct::where('product_id',$products[0][$counter])->first();
	   if($flash_product){
		DB::table('flash_sale_product_orders')->insert(
		['flash_sale_product_id'=>$products[0][$counter],
		'order_id' =>$order_id,
		'qty'=> $products[2][$counter]]);  
	   }
	   $counter++;
   }
   /*********** send notification to admin app with new order message*********/
   
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
		
       
		 return \response()->json(['message' => 'saved succeffly']);	
	  
	}
	
}