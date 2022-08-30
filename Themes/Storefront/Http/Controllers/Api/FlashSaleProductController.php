<?php

namespace Themes\Storefront\Http\Controllers\Api;

use DB;
use Modules\Media\Entities\File;
use Modules\FlashSale\Entities\FlashSale;
use Modules\Review\Entities\Product;
use Modules\Setting\Entities\Setting;
use Modules\Review\Entities\Review;

class FlashSaleProductController

{

    public function index()

    {
       
       $options = Setting::allCached();
        $enabled = $options['storefront_flash_sale_and_vertical_products_section_enabled'];
        $id = $options['storefront_active_flash_sale_campaign'];


        
        $dataa = FlashSale::find($id);
        // $flashSale = $dataa;
         $products = $dataa->products()->get();

        $data = [];
        
        if(isset($products)){
            $productsCount = 0;

            foreach($products as $product){
				
			$rating= Review::select('rating')->where('product_id',$product->id)->first();
		    $sold_qtys = DB::table('flash_sale_product_orders')->where('flash_sale_product_id',$product->id)->get();
				$sold=0;
			   foreach($sold_qtys as $sold_qty){
				$sold += $sold_qty->qty ;
			   }
			   
			    $product->pivot->sold= $sold;
				//dd( $product->pivot); 
                $data[$productsCount]['id'] = $product->id;
                $data[$productsCount]['name'] = $product->name;
                $data[$productsCount]['short_description'] = $product->short_description;
                $data[$productsCount]['base_image'] = $product->base_image->path;
                $data[$productsCount]['selling_price'] = $product->selling_price;
				$data[$productsCount]['rating'] = $rating ;
                $data[$productsCount]['price'] = $product->price;
                $data[$productsCount]['pivot'] = $product->pivot;
                $data[$productsCount]['qty'] = $product->qty;
			    $data[$productsCount]['sold'] = $sold;
			   
                $productsCount++;
            }
        }
       $dataa->flashProducts = $data;
        // storefront_flash_sale_and_vertical_products_section_enabled
        $result = $enabled ? $dataa : null;
        return response()->json($result);

return response()->json($result);

    }

    

    

}