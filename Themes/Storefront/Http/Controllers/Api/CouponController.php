<?php

namespace Themes\Storefront\Http\Controllers\Api;


use Modules\Setting\Entities\Setting;
use Modules\Coupon\Entities\Coupon;
use DB;


class CouponController

{

    public function index()

    {

        $coupons = Coupon::all();

        return \response()->json($coupons);

    }

    

    public function coupon($code)

    {
        
        $coupon = Coupon::where('code',$code)->first();
		$data=[];
		$data['id']=$coupon->id;
		$data['code']=$coupon->code;
		/****to union data model wehen  is_percent changed *****/
		if($coupon->is_percent == 1)
		{
			$data['value']['amount']=$coupon->value;
			$data['value']['formatted']=0;
			$data['value']['currency']=0;
			$data['value']['inCurrentCurrency']['amount']=0;
			$data['value']['inCurrentCurrency']['formatted']=0;
			$data['value']['inCurrentCurrency']['currency']=0;
				
		}
		else{
       $data['value']=$coupon->value;
		}
		$data['is_percent']=$coupon->is_percent;
		$data['free_shipping']=$coupon->free_shipping;
		$data['minimum_spend']=$coupon->minimum_spend;
		$data['maximum_spend']=$coupon->maximum_spend;
		$data['usage_limit_per_coupon']=$coupon->usage_limit_per_coupon;
		$data['usage_limit_per_customer']=$coupon->usage_limit_per_customer;
		$data['used']=$coupon->used;
		$data['is_active']=$coupon->is_active;
		$data['start_date']=$coupon->start_date;
		$data['end_date']=$coupon->end_date;
		$data['translations']=$coupon->translations;
		//$data['value']['formatted']=null;
        return \response()->json($data);

    }

    public function products($id)
    {
        $products = DB::table('coupon_products')->where('coupon_id',$id)->get();

        return \response()->json($products);
    }
    
	public function categories($id)
    {
        $categories = DB::table('coupon_categories')->where('coupon_id',$id)->get();

        return \response()->json($categories);
    }
	/***************** return translation of lables ****************/
	public function localTranslation($id)
    {
		$lables = DB::table('setting_translations')->where('setting_id',$id)->get();
		$locale=[];
		foreach($lables as $lable){
			
		$locale[]=["locale"=>$lable->locale,"value"=>unserialize($lable->value)];
		}
		return $locale;
	}
	/********************* return shipping cost details *******/
	public function shippingCost()
    {
		$shipping_keys=['local_pickup_label','local_pickup_enabled','local_pickup_cost','free_shipping_label','free_shipping_enabled' ,
		                'free_shipping_min_amount','flat_rate_label','flat_rate_enabled','flat_rate_cost'];
		
		$shipping_values = DB::table('settings')->wherein('key',$shipping_keys)->get();
		 /*$options = Setting::allCached();
        $enabled = $options['local_pickup_label'];*/
		$shipping_details=['flat_rate_cost'=>unserialize($shipping_values[0]->plain_value),
		                   'flat_rate_enabled'=>unserialize($shipping_values[1]->plain_value),
		                   'flat_rate_label'=>$this->localTranslation($shipping_values[2]->id),
						   'free_shipping_min_amount'=>unserialize($shipping_values[3]->plain_value),
						   'free_shipping_enabled'=>unserialize($shipping_values[4]->plain_value),
						   'free_shipping_label'=>$this->localTranslation($shipping_values[5]->id),
						   'local_pickup_cost'=>unserialize($shipping_values[6]->plain_value),
						   'local_pickup_enabled'=>unserialize($shipping_values[7]->plain_value),
						   'local_pickup_label'=>$this->localTranslation($shipping_values[8]->id)
		];
		
		
		 return \response()->json($shipping_details);
	}
}