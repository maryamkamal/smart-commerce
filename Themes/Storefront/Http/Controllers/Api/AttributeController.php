<?php

namespace Themes\Storefront\Http\Controllers\Api;

use Modules\Attribute\Entities\Attribute;
use Modules\Attribute\Entities\ProductAttribute;
use Modules\Attribute\Entities\ProductAttributeValue;
use Modules\Media\Entities\File;
use Modules\Category\Entities\Category;
use Modules\Support\Eloquent\Translatable;
use DB;
use Illuminate\Http\Request;
use Modules\Product\Entities\Product;




/*

    @var : $category , $categories

*/



class AttributeController

{
    //return attributes and values by category id
     public function categoryAttributes($id){
        $atts= DB::table('attribute_categories')->select('attribute_id')->where('category_id',$id)->get();
        foreach($atts as $att){
           
       $data[]= $this->attributes($att->attribute_id);
        
        }
         return \response()->json($data);
     }
      public function attributes($id)
    {
       
        $attributes = Attribute::with('values')->where('id',$id)->get();
        
         $data = [];
        if($attributes){
            $count = 0;
            foreach($attributes as $item){
                $data[$count]['id'] = $item->id;
                $data[$count]['name'] = $item->name;
                $data[$count]['slug'] = $item->slug;
                if($item->translations){
                    $transCount = 0;
                    foreach($item->translations as $trans){
                        $data[$count]['translations'][$transCount]['locale'] = $trans->locale;
                        $data[$count]['translations'][$transCount]['name'] = $trans->name;
                        $transCount++;
                    }
                }
                $values = $item->values()->get();
                if($values){
                    $valuesCount = 0;
                    foreach($values as $value){
                        $data[$count]['values']['data'][$valuesCount]['id'] = $value->id;
                        $data[$count]['values']['data'][$valuesCount]['name'] = $value->name;
                        $data[$count]['values']['data'][$valuesCount]['slug'] = $value->slug;
                        

                        if($value->translations){
                            $transCount = 0;
                            foreach($value->translations as $trans){
                                $data[$count]['values']['data'][$valuesCount]['translations'][$transCount]['locale'] = $trans->locale;
                                $data[$count]['values']['data'][$valuesCount]['translations'][$transCount]['name'] = $trans->name;
                                $transCount++;
                            }
                        }
                        $valuesCount++;
                    }
                   
                }
                $count++;
            }
        }

        return $data ;


    }
     public function productsAttributes(/*Request $request*/){
       
         $ids=[8,7,6,10,20,25];
         
           $attributes = Attribute::with('values')->wherein('id',$ids)->get();
        
         
        if($attributes){
            $count = 0;
            foreach($attributes as $item){
            
                $values = $item->values()->get();
                if($values){
                    $valuesCount = 0;
                    foreach($values as $value){
                      //$data[]= $value->id;
                        $values_products[]= DB::table('product_attribute_values')->select('product_attribute_id')->where('attribute_value_id',$value->id)->get(); 
                        foreach($values_products as $values_product){
                       // $attributes_products[]= DB::table('product_attributes')->select('product_id')->where('id',$values_product->product_attribute_id)->get(); 
                        }
                        //$products[] = Product::with('files')->where('id',$attributes_products[$valuesCount])->get();
                        $valuesCount++;
                        }
                        
                    }
                   $count++;
                }
           
        }
      /***************************/
         /* for($x=0;$x<$counts;$x++){
          $attributes_products[] = Product::with('files')->where('id',$attributes[$x]->product_id)->get();
          
     }*/

          return \response()->json($products);
    
}
}