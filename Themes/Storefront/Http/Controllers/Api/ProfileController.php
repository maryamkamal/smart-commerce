<?php
namespace Themes\Storefront\Http\Controllers\Api;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Modules\User\Entities\User;
use Modules\Review\Entities\Review;
use Modules\Order\Entities\Order;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use DB;
class ProfileController
{
   // protected $user;

   /* public function __construct(Request $request)
    {
        $userToken = $request->header('api_token');
        $user = User::where('api_token',$userToken)->first();

        $this->user = $user;
    }*/

    public function profile(Request $request)
    {
		 $apiToken = $request->api_token;
        $user = User::where('api_token',$apiToken)->first();
		
        $data =[];
        
        $data['id'] = $user->id;
        $data['first_name'] = $user->first_name;
        $data['last_name'] = $user->last_name;
        $data['email'] = $user->email;
        $data['gender'] = $user->gender;
        $data['birthday'] = $user->birthday;
        $data['saved_coupons'] = $user->saved_coupons;
        $data['last_login'] = $user->last_login;

         $coupons = $user->saved_coupons;
        // if($coupons){
        //     foreach(\json_decode($coupons) as $item){
        //         $userCoupons[] = Coupon::find($item);
        //     }
        //     $saved =  $userCoupons;
        // }else{
        //     $saved = null;
        // }

        $data['saved_coupons'] = $coupons ? count(json_decode($coupons)) : 0;
        $data['last_login'] = $user->last_login;
        $data['reviews_count'] = Review::where('reviewer_id',$user->id)->count();

        // $data['reviews'] = Review::where('reviewer_id',$this->user->id)->offset(0)->limit(3)->get();
        $data['wishlist'] = [];
        if(!is_null($user->wishlist)){
            $productsCount = 0;
            
            // $data['wishlist'][$productsCount]['upsell'] = [];
            foreach($user->wishlist as $product){
                $data['wishlist'][$productsCount]['id'] = $product->id;
                $data['wishlist'][$productsCount]['name'] = $product->name;
                $data['wishlist'][$productsCount]['short_description'] = $product->short_description;
                // $data['wishlist'][$productsCount]['category_id'] = $product->pivot->category_id;
                $data['wishlist'][$productsCount]['base_image'] = $product->base_image->path;
                $data['wishlist'][$productsCount]['brand_id'] = $product->brand_id;
                $data['wishlist'][$productsCount]['tax_class_id'] = $product->tax_class_id;
                $data['wishlist'][$productsCount]['selling_price'] = $product->selling_price;
                $data['wishlist'][$productsCount]['price'] = $product->price;
 
                if($product->translations){
                    $transCount = 0;
                    foreach($product->translations as $trans){
                        $data['wishlist'][$productsCount]['translations'][$transCount]['locale'] = $trans->locale;
                        $data['wishlist'][$productsCount]['translations'][$transCount]['name'] = $trans->name;
                        $data['wishlist'][$productsCount]['translations'][$transCount]['short_description'] = $trans->short_description;
                        $transCount++;
                    }
                }
                $productsCount++;
            }
        }
        
        //  $data['orders'] = $this->user->orders()->offset(0)->limit(3)->get();

        // $this->user->wishlist;
        // $this->user->orders = $this->user->orders()
        // ->latest()
        // ->paginate(20);

        
        // dd($data);
        return \response()->json($data);

    }
                // 'last_name' => 'required',
                // 'email' => 'required|email|unique:users',
                // 'password' => 'required|confirmed|min:6',
                
    public function update(Request $request)
    {
		 $apiToken = $request->api_token;
		
        $user = User::where('api_token',$apiToken)->first();
		 //dd($user);
        if(request()->has('first_name')){

            $validator = Validator::make($request->all(),[
                'first_name' => 'string',
            ]);
    
            if($validator->fails()){
                return \response()->json($validator->messages());
            }
            
            $user->first_name = request()->get('first_name');
        }

        if(request()->has('last_name')){

            $validator = Validator::make($request->all(),[
                'last_name' => 'string',
            ]);
    
            if($validator->fails()){
                return \response()->json($validator->messages());
            }
            
            $user->last_name = request()->get('last_name');
        }
         
		 if(request()->has('city')){

            $validator = Validator::make($request->all(),[
                'city' => 'string',
            ]);
    
            if($validator->fails()){
                return \response()->json($validator->messages());
            }
            
            $user->city = request()->get('city');
        }
        
             if(request()->has('district')){

            $validator = Validator::make($request->all(),[
                'district' => 'string',
            ]);
    
            if($validator->fails()){
                return \response()->json($validator->messages());
            }
            
            $user->district = request()->get('district');
        }
        
               if(request()->has('street')){

            $validator = Validator::make($request->all(),[
                'street' => 'string',
            ]);
    
            if($validator->fails()){
                return \response()->json($validator->messages());
            }
            
            $user->street = request()->get('street');
        }
		
		if(request()->has('address_line')){

            $validator = Validator::make($request->all(),[
                'address_line' => 'string',
            ]);
    
            if($validator->fails()){
                return \response()->json($validator->messages());
            }
            
            $user->address_line = request()->get('address_line');
        }
		
         if(request()->has('phone_number')){

            $validator = Validator::make($request->all(),[
                'phone_number' => 'numeric',
            ]);
    
            if($validator->fails()){
                return \response()->json($validator->messages());
            }
            
            $user->phone_number = request()->get('phone_number');
        }
		
		if(request()->has('house_number')){

            $validator = Validator::make($request->all(),[
                'house_number' => 'numeric',
            ]);
    
            if($validator->fails()){
                return \response()->json($validator->messages());
            }
            
            $user->house_number = request()->get('house_number');
        }
        
        if(request()->has('apatment_number')){

            $validator = Validator::make($request->all(),[
                'apatment_number' => 'numeric',
            ]);
    
            if($validator->fails()){
                return \response()->json($validator->messages());
            }
            
            $user->apatment_number = request()->get('apatment_number');
        }
        if(request()->has('distinct_mark')){

            
            $user->distinct_mark= request()->get('distinct_mark');
        }
		
	if(request()->has('zipcode')){

            $validator = Validator::make($request->all(),[
                'zipcode' => 'numeric',
            ]);
    
            if($validator->fails()){
                return \response()->json($validator->messages());
            }
            
            $user->zipcode = request()->get('zipcode');
        }
		
        if(request()->has('birthday')){

            $validator = Validator::make($request->all(),[
                'birthday' => 'date_format:d-m-Y',
            ]);
    
            if($validator->fails()){
                return \response()->json($validator->messages());
            }
            
            $user->birthday = Carbon::parse(request()->get('birthday'));
        }

        if(request()->has('gender')){

            $validator = Validator::make($request->all(),[
                'gender' => 'in:male,female',
            ]);
    
            if($validator->fails()){
                return \response()->json($validator->messages());
            }
            
            $user->gender = request()->get('gender');
        }

        if(request()->has('email')){

            $validator = Validator::make($request->all(),[
                'email' => 'email|unique:users',
            ]);
    
            if($validator->fails()){
                return \response()->json($validator->messages());
            }
            
            $user->email = request()->get('email');
        }

        if(request()->has('password')){

            $validator = Validator::make($request->all(),[
                'password' => 'confirmed|min:6',
            ]);
    
            if($validator->fails()){
                return \response()->json($validator->messages());
            }
            
            $user->password = Hash::make(request()->get('password'));
        }
        
		if(request()->has('mobile_token')){

            $validator = Validator::make($request->all(),[
                'mobile_token' => 'string',
            ]);
    
            if($validator->fails()){
                return \response()->json($validator->messages());
            }
            
            $user->mobile_token = request()->get('mobile_token');
        }
        $user->save();


            return \response()->json(['message' => 'updated']);

    }
   public function userReviews(Request $request)
    {
		$user = User::where('api_token',$request->api_token)->first();
        $reviews =  Review::where('reviewer_id',$user->id)->get();
        return \response()->json($reviews);
    }
    public function wishlistStore(Request $request)
    {
		 $apiToken = $request->api_token;
        $user = User::where('api_token',$apiToken)->first();
		$check_wishlist= DB:: table('wish_lists')->select('product_id','user_id')->where('user_id',$user->id)->where( 'product_id',request()->get('product_id'))->first();
		
		if(empty($check_wishlist)){
		DB:: table('wish_lists')->insert(
        ['user_id' =>$user->id, 'product_id' => request()->get('product_id')]);
            return \response()->json(['message' => 'stored_successfull']);
        }else{
            return \response()->json(['message' => 'already_stored']); 
        }

    }

    public function wishlistDelete(Request $request)
    {
            $apiToken = $request->api_token;
            $user = User::where('api_token',$apiToken)->first();
			$check_wishlist= DB:: table('wish_lists')->where('user_id',$user->id)->where( 'product_id',request()->get('product_id'))->delete();
            
            return \response()->json(['message' => 'deleted_successfull']);
        
    }

   /* public function saveCoupon(Request $request)
    {
		 $apiToken = $request->api_token;
        $user = User::where('api_token',$apiToken)->first();
        $validator = Validator::make($request->all(),[
            'code' => 'string|required',
        ]);

        if($validator->fails()){
            return \response()->json($validator->messages());
        }

        $savedCoupons = $user->saved_coupons;

        if(!$savedCoupons){
            $saved = [$request->code];
            $user->saved_coupons = \json_encode($saved);
        }else{
            $saved = json_decode($savedCoupons);
            if(!in_array($request->code,$saved)){
                $saved[] = $request->code;
                $user->saved_coupons = \json_encode($saved);
            }else{
                return \response()->json(['message' => 'already_exists']);
            }
            
        }

        $user->save();
        return \response()->json(['message' => 'saved']);
    }*/
	
    public function userCoupons(Request $request)
    {
		 $apiToken = $request->api_token;
        $user = User::where('api_token',$apiToken)->first();
        $coupons = $user->saved_coupons;
        if($coupons){
            foreach(\json_decode($coupons) as $item){
                $cop = Coupon::find($item);
                unset($cop->translations);
                $userCoupons[] = $cop;
            }
            $saved =  $userCoupons;
        }else{
            $saved = null;
        }

        return response()->json($saved);
    }
    public function deleteCoupon($key)
    {
         $apiToken = $request->api_token;
        $user = User::where('api_token',$apiToken)->first();
        $saved= json_decode($user->saved_coupons);
        if(isset($saved[$key])){
            unset($saved[$key]);
            $user->saved_coupons = $saved;
            $user->save();
            return \response()->json(['message' => 'removed_successfully']);
        }else{
            return \response()->json(['message' => 'index_not_found']);   
        }


    }
	  public function userorders(Request $request)
    {
		 $apiToken = $request->api_token;
        $user = User::where('api_token',$apiToken)->first();
		$order=Order::where('customer_id',$user->id)->get();
		return \response()->json($order);   
	}
		
		
    
}