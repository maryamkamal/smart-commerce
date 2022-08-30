<?php

namespace Themes\Storefront\Http\Controllers\Api;



use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Review\Entities\Review;
use Modules\User\Entities\User;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\ProductTranslation;
use Modules\Order\Entities\OrderProduct;
use Modules\FlashSale\Entities\FlashSale;
use Modules\Setting\Entities\Setting;



class ProductController

{
    public function reviews($id)
    {
      return  Product::findOrFail($id)
                        ->reviews()
                        ->orderBy('created_at','desc')
                        ->paginate(10);

    }
    

	  public function cartProduct(Request $request)

    {
     
       
		$ids=$request->product_id;
		
		 if(is_array($ids)) {
        $products = Product::with( 'files' )->wherein('id',$ids)->get();
		 }
		 else{
			$products = Product::with( 'files' )->where('id',$ids)->get();
		 }
		 $count = 0;
        $productsCount = 0;
                    foreach($products as $product){
                        $data[$count]['products']['data'][$productsCount]['id'] = $product->id;
                        $data[$count]['products']['data'][$productsCount]['name'] = $product->name;
                        $data[$count]['products']['data'][$productsCount]['short_description'] = $product->short_description;
                        $data[$count]['products']['data'][$productsCount]['base_image'] = $product->base_image->path;
                        $data[$count]['products']['data'][$productsCount]['brand_id'] = $product->brand_id;
                        $data[$count]['products']['data'][$productsCount]['tax_class_id'] = $product->tax_class_id;
                        $data[$count]['products']['data'][$productsCount]['formatted_price'] = $product->price;
						$data[$count]['products']['data'][$productsCount]['selling_price'] = $product->selling_price;
                        $data[$count]['products']['data'][$productsCount]['special_price'] = $product->special_price;

                        if($product->translations){
                            $transCount = 0;
                            foreach($product->translations as $trans){
                                $data[$count]['products']['data'][$productsCount]['translations'][$transCount]['locale'] = $trans->locale;
                                $data[$count]['products']['data'][$productsCount]['translations'][$transCount]['name'] = $trans->name;
                                $data[$count]['products']['data'][$productsCount]['translations'][$transCount]['short_description'] = $trans->short_description;
                                $transCount++;
                            }
                        }
                        $productsCount++;
                    }
           
        return \response()->json($data);

    }

   
  /* public function getproduct($id,$api_token)

    {
                 
       
        $product = Product::with([

            'brand' => function($query){
                return $query->with('files');
            },

            'categories',

            'taxClass',

            'tags',
            
            'options'
			
            ])->find($id);

            $product->viewed += 1;
            $product->save();
            
           

             $user = User::where('api_token',$api_token)->first();
			 
			 if(!empty($user)){
	         $check_wishlist= DB:: table('wish_lists')->where('user_id',$user->id)->where( 'product_id',$id)->first();
		        
		         if(empty($check_wishlist)){
		           $product->wishlist=0;
                 }
				 else{
                  $product->wishlist=1;
                  }
			 }
			 else{
                  $product->wishlist=0;
                  }
                
			      
        return \response()->json($product);

    }*/
    public function productCart($id)

    {

        
        $data = [];
        $product = Product::where('id',$id)->first();

        if($product){
            $data['price'] = $product->price;
            $data['selling_price'] = $product->selling_price;
            $data['special_price'] = $product->selling_price;
            $data['manage_stock'] = $product->manage_stock;
            $data['in_stock'] = $product->in_stock;
            $data['qty'] = $product->qty;
        }

            // return \response()->json($product);

        return \response()->json($data);

    }
	
	
   public function orederProduct($id)

    {
     
            $order_products= OrderProduct::where('order_id',$id)->get();
			
		
        $productsCount = 0;
                    foreach($order_products as $order_product){
						$product = Product::with( 'files' )->where('id',$order_product->product_id)->first();
						 $data[$productsCount]['id'] = $product->id;
						$data[$productsCount]['unit_price'] = $order_product->unit_price;
                        $data[$productsCount]['qty'] = $order_product->qty;
                        $data[$productsCount]['line_total'] = $order_product->line_total;
                        $data[$productsCount]['name'] = $product->name;
                        $data[$productsCount]['short_description'] = $product->short_description;
                        $data[$productsCount]['base_image'] = $product->base_image->path;
                        

                        if($product->translations){
                            $transCount = 0;
                            foreach($product->translations as $trans){
                                $data[$productsCount]['translations']['locale'] = $trans->locale;
                                $data[$productsCount]['translations']['name'] = $trans->name;
                                $data[$productsCount]['translations']['short_description'] = $trans->short_description;
                                $transCount++;
                            }
                        }
					
                        $productsCount++;
                    }
           
        return \response()->json($data);

    }
	public function search(Request $request)
    {
	  
		$search_title =$request->search_title;
       
       $product_ids = ProductTranslation::select('product_id')->where('name','like','%'. $search_title .'%')
	   ->orWhere('description','like','%'. $search_title .'%')
	   ->orWhere('short_description','like','%'. $search_title .'%')->get();
	   $products = Product::with( 'files' )->wherein('id',$product_ids)->get();
   
            
			if($products){
                    $productsCount = 0;
                    foreach($products as $product){
					
                        $data['products']['data'][$productsCount]['id'] = $product->id;
                        $data['products']['data'][$productsCount]['name'] = $product->name;
                        $data['products']['data'][$productsCount]['short_description'] = $product->short_description;
                       // $data['products']['data'][$productsCount]['category_id'] = $product->pivot->category_id;
                        $data['products']['data'][$productsCount]['base_image'] = $product->base_image->path;
                        $data['products']['data'][$productsCount]['brand_id'] = $product->brand_id;
                        $data['products']['data'][$productsCount]['tax_class_id'] = $product->tax_class_id;
                        $data['products']['data'][$productsCount]['formatted_price'] = $product->price;
						$data['products']['data'][$productsCount]['selling_price'] = $product->selling_price;
                        $data['products']['data'][$productsCount]['special_price'] = $product->special_price;
						if( $request->api_token!=null){
                        $apiToken = $request->api_token;
                         $user = User::where('api_token',$apiToken)->first();
	                    $check_wishlist= DB:: table('wish_lists')->where('user_id',$user->id)->where( 'product_id',$product->id)->first();
		
	        if(empty($check_wishlist)){
				 $data['products']['data'][$productsCount]['wishlist'] = 0;
		        
           }else{
             $data['products']['data'][$productsCount]['wishlist'] = 1;
        }
            }
			else {
				$data['products']['data'][$productsCount]['wishlist'] = 0;
			}

                        if($product->translations){
                            $transCount = 0;
                            foreach($product->translations as $trans){
                                $data['products']['data'][$productsCount]['translations'][$transCount]['locale'] = $trans->locale;
                                $data['products']['data'][$productsCount]['translations'][$transCount]['name'] = $trans->name;
                                $data['products']['data'][$productsCount]['translations'][$transCount]['short_description'] = $trans->short_description;
                                $transCount++;
                            }
                        }
                        $productsCount++;

                    }
                     return \response()->json($data);
                }
				else{
				
                   return \response()->json("not found");
				}
    }
	
    public function getRelatedProducts($id)
    {
        $product = Product::findOrFail($id);
        $data = [];
        if(isset($product->relatedProducts)){
            $productsCount = 0;
            foreach($product->relatedProducts as $productz){
                $data[$productsCount]['id'] = $productz->id;
                $data[$productsCount]['name'] = $productz->name;
                $data[$productsCount]['short_description'] = $productz->short_description;
                // $data[$productsCount]['category_id'] = $productz->pivot->category_id;
                $data[$productsCount]['base_image'] = $productz->base_image->path;
                $data[$productsCount]['brand_id'] = $productz->brand_id;
                $data[$productsCount]['tax_class_id'] = $productz->tax_class_id;
                $data[$productsCount]['selling_price'] = $productz->selling_price;
                $data[$productsCount]['price'] = $productz->price;
                // $data[$productsCount]['supplier'] = $productz->supplier;

                if($productz->translations){
                    $transCount = 0;
                    foreach($productz->translations as $trans){
                        $data[$productsCount]['translations'][$transCount]['locale'] = $trans->locale;
                        $data[$productsCount]['translations'][$transCount]['name'] = $trans->name;
                        $data[$productsCount]['translations'][$transCount]['short_description'] = $trans->short_description;
                        $transCount++;
                    }
                }
                $productsCount++;
            }
            
        }else{
            $data = [];
        }

        return response()->json($data);
    }

    public function getUpSellProducts($id)
    {
        $product = Product::findOrFail($id);
        $data = [];
        if($product->upSellProducts){
            $productsCount = 0;
            foreach($product->upSellProducts as $productz){
                $data[$productsCount]['id'] = $productz->id;
                $data[$productsCount]['name'] = $productz->name;
                $data[$productsCount]['short_description'] = $productz->short_description;
                // $data[$productsCount]['category_id'] = $productz->pivot->category_id;
                $data[$productsCount]['base_image'] = $productz->base_image->path;
                $data[$productsCount]['brand_id'] = $productz->brand_id;
                $data[$productsCount]['tax_class_id'] = $productz->tax_class_id;
                $data[$productsCount]['selling_price'] = $productz->selling_price;
                $data[$productsCount]['price'] = $productz->price;
                // $data[$productsCount]['supplier'] = $productz->supplier;

                if($productz->translations){
                    $transCount = 0;
                    foreach($productz->translations as $trans){
                        $data[$productsCount]['translations'][$transCount]['locale'] = $trans->locale;
                        $data[$productsCount]['translations'][$transCount]['name'] = $trans->name;
                        $data[$productsCount]['translations'][$transCount]['short_description'] = $trans->short_description;
                        $transCount++;
                    }
                }
                $productsCount++;
            }
        }else{
            $data = [];
        }


        return response()->json($data);
    }

    public function getCrossSellProducts($id)
    {
        $product = Product::findOrFail($id);
        $data = [];
        if($product->crossSellProducts){
            $productsCount = 0;
            foreach($product->crossSellProducts as $productz){
                $data[$productsCount]['id'] = $productz->id;
                $data[$productsCount]['name'] = $productz->name;
                $data[$productsCount]['short_description'] = $productz->short_description;
                // $data[$productsCount]['category_id'] = $productz->pivot->category_id;
                $data[$productsCount]['base_image'] = $productz->base_image->path;
                $data[$productsCount]['brand_id'] = $productz->brand_id;
                $data[$productsCount]['tax_class_id'] = $productz->tax_class_id;
                $data[$productsCount]['selling_price'] = $productz->selling_price;
                $data[$productsCount]['price'] = $productz->price;
                // $data[$productsCount]['supplier'] = $productz->supplier;

                if($productz->translations){
                    $transCount = 0;
                    foreach($productz->translations as $trans){
                        $data[$productsCount]['translations'][$transCount]['locale'] = $trans->locale;
                        $data[$productsCount]['translations'][$transCount]['name'] = $trans->name;
                        $data[$productsCount]['translations'][$transCount]['short_description'] = $trans->short_description;
                        $transCount++;
                    }
                }
                $productsCount++;
            }
        }else{
            $data = [];
        }

        return response()->json($data);
    }
    
}