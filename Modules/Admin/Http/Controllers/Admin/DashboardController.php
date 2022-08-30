<?php

namespace Modules\Admin\Http\Controllers\Admin;

use Modules\User\Entities\User;
use Modules\Order\Entities\Order;
use Modules\Review\Entities\Review;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\Supplier;
use Modules\Product\Entities\SearchTerm;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\URL;
use Modules\Media\Entities\File;
use DB;
use Artisan;

class DashboardController
{
    /**
     * Display the dashboard with its widgets.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		    Artisan::call('cache:clear');
			Artisan::call('route:clear');
			Artisan::call('view:clear');
				 
        return view('admin::dashboard.index', [
            'totalSales' => Order::totalSales(),
            'totalOrders' => Order::withoutCanceledOrders()->count(),
            'totalProducts' => Product::withoutGlobalScope('active')->count(),
            'totalCustomers' => User::totalCustomers(),
            'latestSearchTerms' => $this->getLatestSearchTerms(),
            'latestOrders' => $this->getLatestOrders(),
            'latestReviews' => $this->getLatestReviews(),
            'supplierOrders' => $this->supplierOrders(),
            'notifications' =>DB::table('notifications')->orderBy('id', 'desc')->get(),
        ]);
    }

    private function getLatestSearchTerms()
    {
        return SearchTerm::latest('updated_at')->take(5)->get();
    }

    /**
     * Get latest five orders.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function getLatestOrders()
    {
        return Order::select([
            'id',
            'customer_first_name',
            'customer_last_name',
            'total',
            'status',
            'created_at',
        ])->latest()->take(5)->get();
    }

    /**
     * Get latest five reviews.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function getLatestReviews()
    {
        return Review::select('id', 'product_id', 'reviewer_name', 'rating')
            ->has('product')
            ->with('product:id')
            ->limit(5)
            ->get();
    }
    
        public function supplierOrders()
    {
        
        $start_date = request()->has('start_date') ? request()->start_date : null;
        $end_date = request()->has('end_date') ? request()->end_date : null;
        $day = request()->has('day') ? request()->day : null;

        $validate = Validator::make(request()->all(),[
            'start_date' => 'date|required_with:end_date',
            'end_date' => 'date|required_with:start_date',
            'day' => 'date'
        ]);
        if(isset($validate->errors)){
            return false;
        }
        $data = [];
        $suppliers = Supplier::all();
        // dd($suppliers);
        // dd(Carbon::parse($day)->format('Y-m-d H:i'));
        // $now = Carbon::now();
        
        
        return $data;
    }
	
        public function sendNotification()
    {
		$validate = Validator::make(request()->all(),[
            'title' => 'string|max:255',
            'message' => 'string|max:255',
            
        ]);
	
        if($validate->fails()){
            return back()->withErrors($validate);
        }
        $firebaseToken = DB::table('mobile_tokens')->whereNotNull('token')->pluck('token')->all();

        $SERVER_API_KEY = "AAAA0ZxJBvk:APA91bEqBxW4Ni3YpNHoZkJq2DTKt42jOj993C-nlymN0uKFBFf-162bnJPHlsA6dv3RunlJO08ZWXPh4Tr_ZqdwbJvsQzKMoWEyjVAsBnD-qch2xlDI5iEsmW3zpfngAzOYcXf8Ek3-";
       /*  $logo = File::findOrNew(setting('storefront_header_logo'))->path;
	if(request()->image){
       $imageName = time().'.'.request()->image->extension();  
       request()->image->move(public_path('storage/media'), $imageName);
	    $image = env('APP_URL').'/storage/media/'.$imageName;
	 }
	 else{
		 $imageName=null;
		 $image= $logo;
	 }*/
	 
	
        $data = [

            "registration_ids" =>$firebaseToken ,

            "data" => [

                "title" => request()->title,
                "body" => request()->message, 
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
         
		DB::table('notifications')->insert(
      ['title' => request()->title,
      'message' => request()->message,
	  "image" =>null]
     );
	 
        return back();
  
    }
}
