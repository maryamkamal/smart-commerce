<?php

namespace Themes\Storefront\Http\Controllers\Api;


use Illuminate\Http\Request;
use Modules\User\Entities\User;
use Illuminate\Support\Facades\DB;
use Modules\Brand\Entities\Brand;







class BrandController

{

    public function index()

    {

        $brands = Brand::with('products','files')->get();
        $data = [];
        if($brands){
            $count = 0;
            foreach($brands as $item){
                $data[$count]['id'] = $item->id;
                $data[$count]['name'] = $item->name;
                if($item->translations){
                    $transCount = 0;
                    foreach($item->translations as $trans){
                        $data[$count]['translations'][$transCount]['locale'] = $trans->locale;
                        $data[$count]['translations'][$transCount]['name'] = $trans->name;
                        $transCount++;
                    }
                }

                if($item->files){
                    $filesCount = 0;
                    foreach($item->files as $file){
                        $data[$count]['files'][$filesCount] = $file->path;
                    }
                }
                
                $count++;
            }
        }

        return \response()->json($data);

    }

    // get brand all product

    public function products($id,Request $request)

    {
        $brand = Brand::with('products','files')->where('id',$id)->first();
        $data = [];
        $data['id'] = $brand->id;
        $data['name'] = $brand->name;
        if($brand->translations){
            $transCount = 0;
            foreach($brand->translations as $trans){
                $data['translations'][$transCount]['locale'] = $trans->locale;
                $data['translations'][$transCount]['name'] = $trans->name;
                $transCount++;
            }
        }

        if($brand->files){
            $filesCount = 0;
            foreach($brand->files as $file){
                $data['files'][$filesCount] = $file->path;
            }
        }
        $products = $brand->products()->paginate(200);
        if($products){
            $productsCount = 0;
            foreach($products as $product){
                $data['products']['data'][$productsCount]['id'] = $product->id;
                $data['products']['data'][$productsCount]['name'] = $product->name;
                $data['products']['data'][$productsCount]['short_description'] = $product->short_description;
                // $data['products'][$productsCount]['category_id'] = $product->pivot->category_id;
                $data['products']['data'][$productsCount]['base_image'] = $product->base_image->path;
                $data['products']['data'][$productsCount]['brand_id'] = $product->brand_id;
                $data['products']['data'][$productsCount]['tax_class_id'] = $product->tax_class_id;
                $data['products']['data'][$productsCount]['formatted_price'] = $product->price;
				$data['products']['data'][$productsCount]['selling_price'] = $product->selling_price;
                $data['products']['data'][$productsCount]['special_price'] = $product->special_price ?? null;
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
            $data['products']['next_page_url'] = $products->nextPageUrl();
            $data['products']['prev_page_url'] = $products->previousPageUrl();
            $data['products']['last_page'] = $products->lastPage();
        }else{
          $data['products'] = [];
        }
        
        // $count++;
    
        return \response()->json($data);

    }
    
    
public function brand($id)

    {

        $brand = Brand::with('files')->where('id',$id)->first();
        $data = [];
        $data['id'] = $brand->id;
        $data['name'] = $brand->name;
        if($brand->translations){
            $transCount = 0;
            foreach($brand->translations as $trans){
                $data['translations'][$transCount]['locale'] = $trans->locale;
                $data['translations'][$transCount]['name'] = $trans->name;
                $transCount++;
            }
        }

        if($brand->files){
            $filesCount = 0;
            foreach($brand->files as $file){
                $data['files'][$filesCount] = $file->path;
            }
        }
        
        
        // $count++;
    
        return \response()->json($data);

    }
}