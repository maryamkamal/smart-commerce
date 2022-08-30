<?php

namespace Themes\Storefront\Http\Controllers\Api;
use Modules\Category\Entities\Category;

use DB;



class FeatureController

{

    public function features()

    {
		/********** title *****////
		$feature_1=['storefront_feature_1_title','storefront_feature_1_subtitle','storefront_feature_1_icon'];
		$feature_2=['storefront_feature_2_title','storefront_feature_2_subtitle','storefront_feature_2_icon'];
		$feature_3=['storefront_feature_3_title','storefront_feature_3_subtitle','storefront_feature_3_icon'];
		$feature_4=['storefront_feature_4_title','storefront_feature_4_subtitle','storefront_feature_4_icon'];
		$feature_5=['storefront_feature_5_title','storefront_feature_5_subtitle','storefront_feature_5_icon'];
		
		
		$feature1_ids = DB::table('settings')->select('value')->wherein('key',$feature_1)->get();
		$feature2_ids = DB::table('settings')->select('id')->wherein('key',$feature_2)->get();
		$feature3_ids = DB::table('settings')->select('id')->wherein('key',$feature_3)->get();
		$feature4_ids = DB::table('settings')->select('id')->wherein('key',$feature_4)->get();
		$feature5_ids = DB::table('settings')->select('id')->wherein('key',$feature_5)->get();
		
/*	foreach($feature1_ids as $feature1_id){
		$feature_titles[]= DB::table('setting_translations')->select('locale','value')->where('setting_id',$feature1_id->id)->get();
	}*/
	
	
		foreach($feature1_ids as $feature_title){
	$title=[
	   
		'title'=>$feature_title->value
		];
		
		
		}
		 /*******************subtitle***/////////////////////
	
		$att=[$title];
		 return \response()->json($att);
	}
	public function categories()

    {
		$cat_key=['storefront_featured_categories_section_category_1_category_id','storefront_featured_categories_section_category_2_category_id','storefront_featured_categories_section_category_3_category_id','storefront_featured_categories_section_category_4_category_id','storefront_featured_categories_section_category_5_category_id', 'storefront_featured_categories_section_category_6_category_id'];
		
		$cat_values = DB::table('settings')->select('plain_value')->wherein('key',$cat_key)->get();
		
		foreach($cat_values as $cat_value){
		$categories_id= unserialize($cat_value->plain_value);
	    
	    $category = Category::with('files')->find($categories_id);
       $transCount = 0;
            if($category){
				 $cat= [
                'id'=> $category->id,
                'parent_id' => $category->parent_id,
                'name' => $category->name,
				'position'=>$category->position,
				'is_searchable'=>$category->is_searchable,
				'is_active'=>$category->is_active,
                'logo' => $category->logo->path,
                'banner' => $category->banner->path];
                if($category->translations){
                    
                    foreach($category->translations as $trans){
					$translation[$transCount]=	[
                        'locale' => $trans->locale,
                       'name' => $trans->name];
                       $transCount++;
                    }
					
                }
           $data[]=  array_merge($cat, $translation);
            }
		}
		
		
		return \response()->json($data);
		
}
}