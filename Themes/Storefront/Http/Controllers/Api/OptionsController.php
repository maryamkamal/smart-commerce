<?php

namespace Themes\Storefront\Http\Controllers\Api;

use Modules\Brand\Entities\Brand;
use Illuminate\Support\Facades\DB;
use Modules\Product\RecentlyViewed;
use Modules\Product\Entities\Product;
use Modules\Setting\Entities\Setting;

class OptionsController
{

    public $options;
    public $recently_added;
    public $most_viewed;

    public function __construct()
    {
        $this->options = Setting::allCached();
        $this->recently_added = $this->recen();
        $this->most_viewed = $this->viewed();
    }

    public function index()
    {
        $all = $this->options;
        
        $return = [
            'social' => [
                'facebook_link' => $all['storefront_facebook_link'],
                'twitter_link' => $all['storefront_twitter_link'],
                'instagram_link' => $all['storefront_instagram_link'],
                'youtube_link' => $all['storefront_youtube_link'],
                'address' => $all['storefront_address'],
                'phone' => $all['store_phone'],
                'address_1' => $all['store_address_1'],
                'address_2' => $all['store_address_2'],
                'city' => $all['store_city'],
                'country' => $all['store_country'],
                'state' => $all['store_state'],
                'zip' => $all['store_zip'],
                'email' => $all['store_email']
            ],
            'featured_categories_section' => [
                'enabled' => $this->changeStatusType($all['storefront_featured_categories_section_enabled']),
                'category_1_id' => $all['storefront_featured_categories_section_category_1_category_id'],
                'category_2_id' => $all['storefront_featured_categories_section_category_2_category_id'],
                'category_3_id' => $all['storefront_featured_categories_section_category_3_category_id'],
                'category_4_id' => $all['storefront_featured_categories_section_category_4_category_id'],
                'category_5_id' => $all['storefront_featured_categories_section_category_5_category_id'],
                'category_6_id' => $all['storefront_featured_categories_section_category_6_category_id'],
            ],
            'top_brands' => [
                'enabled' => $this->changeStatusType($all['storefront_top_brands_section_enabled']),
                'top_brands' => $this->getBrands($all['storefront_top_brands'])
            ],
            'product_tabs_1' =>[
                'enabled' => $this->changeStatusType($all['storefront_product_tabs_1_section_enabled']),
                'tab1' => [
                    'title' => $all['storefront_product_tabs_1_section_tab_1_title'],
                    'category_id' => $all['storefront_product_tabs_1_section_tab_1_category_id'],
                ],
                'tab2' => [
                    'title' => $all['storefront_product_tabs_1_section_tab_2_title'],
                    'category_id' => $all['storefront_product_tabs_1_section_tab_2_category_id'],
                ],
                'tab3' => [
                    'title' => $all['storefront_product_tabs_1_section_tab_3_title'],
                    'category_id' => $all['storefront_product_tabs_1_section_tab_3_category_id'],
                ],
                'tab4' => [
                    'title' => $all['storefront_product_tabs_1_section_tab_4_title'],
                    'category_id' => $all['storefront_product_tabs_1_section_tab_4_category_id'],
                ],
            ],
            'product_tabs_2' =>[
                'enabled' => $this->changeStatusType($all['storefront_product_tabs_2_section_enabled']),
                'tab1' => [
                    'title' => $all['storefront_product_tabs_2_section_tab_1_title'],
                    'category_id' => $all['storefront_product_tabs_2_section_tab_1_category_id'],
                ],
                'tab2' => [
                    'title' => $all['storefront_product_tabs_2_section_tab_2_title'],
                    'category_id' => $all['storefront_product_tabs_2_section_tab_2_category_id'],
                ],
                'tab3' => [
                    'title' => $all['storefront_product_tabs_2_section_tab_3_title'],
                    'category_id' => $all['storefront_product_tabs_2_section_tab_3_category_id'],
                ],
                'tab4' => [
                    'title' => $all['storefront_product_tabs_2_section_tab_4_title'],
                    'category_id' => $all['storefront_product_tabs_2_section_tab_4_category_id'],
                ],
            ],
            'product_grid_section' =>[
                'enabled' => $this->changeStatusType($all['storefront_product_grid_section_enabled']),
                'tap1' =>[
                    'title' => $all['storefront_product_grid_section_tab_1_title'],
                    'category_id' => $all['storefront_product_grid_section_tab_1_category_id'],
                ],
                'tap2' =>[
                    'title' => $all['storefront_product_grid_section_tab_2_title'],
                    'category_id' => $all['storefront_product_grid_section_tab_2_category_id'],
                ],
                'tap3' =>[
                    'title' => $all['storefront_product_grid_section_tab_3_title'],
                    'category_id' => $all['storefront_product_grid_section_tab_3_category_id'],
                ],
                'tap4' =>[
                    'title' => $all['storefront_product_grid_section_tab_4_title'],
                    'category_id' => $all['storefront_product_grid_section_tab_4_category_id'],
                ],
            ],
            'shipping' => [
                'free_shipping_enabled' => $this->changeStatusType($all['free_shipping_enabled']),
                'free_shipping_min_amount' => $all['free_shipping_min_amount'],

            ],
            //'recently_added' => $this->recently_added
        ];
        

        return response()->json($return); // ['featured_products' => $featured_cat ]
    }


    // social
    // featured_categories_section
    // top_brands
    // product_tabs_1
    // product_tabs_2
    // product_grid_section
    // shipping

    public function recentlyAdded()
    {
        return $this->recently_added;
    }

    public function mostViewed()
    {
        return $this->most_viewed;
    }

    public function featuredCategoriesSection()
    {
        # code...
    }

    public function topBrands()
    {
        # code...
    }

    public function productTabs1()
    {
        # code...
    }

    public function productTabs2()
    {
        # code...
    }

    public function productGridSection()
    {
        # code...
    }

    public function shipping()
    {
        # code...
    }

    // protected ->>>
    protected function getBrands($ids)
    {
        if(!$ids){
            return;
        }
        $brands = [];
        foreach($ids as $id){
            $br = Brand::where('id',$id)->with(['files'])->first()->toArray();
            $newBr = $br;
            unset($newBr['translations']);
            $brands[] = $newBr;
        }

        return $brands ? $brands : null;
    }

    protected function changeStatusType($status = null)
    {
        if(is_null($status)){
            return $status;
        }

        return (int) $status ? true : false;

    }

    protected function recen(){
    
        $products = Product::latest()->paginate(10);

        return response()->json($this->buildProducts($products));
    }

    public function viewed()
    {
        $products = Product::orderby('viewed','desc')->paginate(10);

        return response()->json($this->buildProducts($products));
    }

    public function buildProducts($products)
    {
        $data = [];
        if($products){
            $data = [];
            $productsCount = 0;
            foreach($products as $product){
                $data['data'][$productsCount]['id'] = $product->id;
                $data['data'][$productsCount]['name'] = $product->name;
                $data['data'][$productsCount]['short_description'] = $product->short_description;
                // $data['data'][$productsCount]['categories'] = $product->categories;
                $data['data'][$productsCount]['base_image'] = $product->base_image->path;
                $data['data'][$productsCount]['brand_id'] = $product->brand_id;
                $data['data'][$productsCount]['tax_class_id'] = $product->tax_class_id;
                $data['data'][$productsCount]['selling_price'] = $product->selling_price;
                $data['data'][$productsCount]['price'] = $product->price;
                $data['data'][$productsCount]['views'] = $product->viewed;
                // $data['data'][$productsCount]['supplier'] = $product->supplier;
                
                // $data['data'][$productsCount]['special_price'] = $product->special_price;

                // if($product->translations){
                //     $transCount = 0;
                //     foreach($product->translations as $trans){
                //         $data['data'][$productsCount]['translations'][$transCount]['locale'] = $trans->locale;
                //         $data['data'][$productsCount]['translations'][$transCount]['name'] = $trans->name;
                //         $data['data'][$productsCount]['translations'][$transCount]['short_description'] = $trans->short_description;
                //         $transCount++;
                //     }
                // }
                $productsCount++;
            }
            $data['next_page_url'] = $products->nextPageUrl();
            $data['prev_page_url'] = $products->previousPageUrl();
            $data['last_page'] = $products->lastPage();
            $data['total'] = $products->total();
        }else{
          $data['data'] = [];
        }

        return $data;
    }
}