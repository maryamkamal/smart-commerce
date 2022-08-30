<?php

namespace Themes\Storefront\Http\Controllers\Api;


use Illuminate\Http\Request;
use Modules\User\Entities\User;
use Illuminate\Support\Facades\DB;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\File;
use Modules\Category\Entities\Category;
use Artisan;




/*

    @var : $category , $categories

*/



class CategoryController

{

  
	
    public function childsProducts($parent_id,Request $request)
    {
		
        $categories = Category::select('id')->where('parent_id',$parent_id)->get();
        $product_ids = DB::table('product_categories')->wherein('category_id',$categories)->get();
				
             if($product_ids){
             $productsCount = 0;
            foreach($product_ids as $product_id){
                    
		    $product =Product::with('files')->where('id',$product_id->product_id)->first();
			if($product){		
                        $data['products']['data'][$productsCount]['id'] = $product->id;
                        $data['products']['data'][$productsCount]['name'] = $product->name;
                        $data['products']['data'][$productsCount]['short_description'] = $product->short_description;
                        $data['products']['data'][$productsCount]['category_id'] = $id;
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
                        
                   }
                  
                  $productsCount++;
                    }
			}
				
        return \response()->json($data);
    }
	
    public function category($id)

    {
		
        $category = Category::with('products','files')->find($id);
        $data = [];
            if($category){
                $data['id'] = $category->id;
                $data['parent_id'] = $category->parent_id;
                $data['name'] = $category->name;
                $data['logo'] = $category->logo->path;
                $data['banner'] = $category->banner->path;
                if($category->translations){
                    $transCount = 0;
                    foreach($category->translations as $trans){
                        $data['translations'][$transCount]['locale'] = $trans->locale;
                        $data['translations'][$transCount]['name'] = $trans->name;
                        $transCount++;
                    }
                }

               

            }
        return \response()->json($data);

    }

    public function parentOnly()
    {
        $categories = Category::whereNull('parent_id')->with('files')->get();
        return \response()->json($categories);
    }
    
  public function childs($parent_id)
    {

        if(!\is_numeric($parent_id)){
            return \response()->json(['message' => 'must_be_numeric']);
        }
        $categories = Category::with('files')->where('parent_id',$parent_id)->get();
        return \response()->json($categories);
    }
   
    public function categoryProducts($id,Request $request )

    {	
               $product_ids = DB::table('product_categories')->select('product_id')->where('category_id',$id)->get();
			   
                if($product_ids){
					foreach($product_ids as $product_id){
                    $ids[]= $product_id->product_id;
					}
					
		            $products =Product::with('files')->wherein('id',$ids)->where('deleted_at',null)->get();
			        
                    $productsCount = 0;
                    foreach($products as $product){
                        $data['products']['data'][$productsCount]['id'] = $product->id;
                        $data['products']['data'][$productsCount]['name'] = $product->name;
                        $data['products']['data'][$productsCount]['short_description'] = $product->short_description;
                        $data['products']['data'][$productsCount]['category_id'] = $id;
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
			}
				
				
                   return \response()->json($data);
			}
		  		

       
    
  public function parentCategoryProducts($id)

    {
        
        $category = Category::whereNull('parent_id')->with('products','files')->find($id);
       
            if($category){

                $products = $category->products()->paginate(10);

                if($products){
                    $productsCount = 0;
                    foreach($products as $product){
                        $data['products']['data'][$productsCount]['id'] = $product->id;
                        $data['products']['data'][$productsCount]['name'] = $product->name;
                        $data['products']['data'][$productsCount]['short_description'] = $product->short_description;
                        $data['products']['data'][$productsCount]['category_id'] = $product->pivot->category_id;
                        $data['products']['data'][$productsCount]['base_image'] = $product->base_image->path;
                        $data['products']['data'][$productsCount]['brand_id'] = $product->brand_id;
                        $data['products']['data'][$productsCount]['tax_class_id'] = $product->tax_class_id;
                        $data['products']['data'][$productsCount]['formatted_price'] = $product->price;
						$data['products']['data'][$productsCount]['selling_price'] = $product->selling_price;
                        $data['products']['data'][$productsCount]['special_price'] = $product->special_price;

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
                }

            }
        return \response()->json($data);

    }

}