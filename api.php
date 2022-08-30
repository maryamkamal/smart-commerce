<?php

use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;



// Route::group(['middleware' => 'auth:api'],function(){



// });



/* -------- Routes ------------- Controllers ---------------------------------------- Methods */
Route::get('products','\Themes\Storefront\Http\Controllers\Api\ProductController@index');
Route::get('products/sale','\Themes\Storefront\Http\Controllers\Api\ProductController@flashSale');
Route::get('product/{id}','\Themes\Storefront\Http\Controllers\Api\ProductController@product');
Route::get('product/reviews/{id}','\Themes\Storefront\Http\Controllers\Api\ProductController@reviews');
Route::get('product/related/{id}','\Themes\Storefront\Http\Controllers\Api\ProductController@getRelatedProducts');
Route::get('product/upsell/{id}','\Themes\Storefront\Http\Controllers\Api\ProductController@getUpSellProducts');
Route::get('product/cross/{id}','\Themes\Storefront\Http\Controllers\Api\ProductController@getCrossSellProducts');
Route::get('product/mini/{id}','\Themes\Storefront\Http\Controllers\Api\ProductController@productCart');

Route::get('brands','\Themes\Storefront\Http\Controllers\Api\BrandController@index');
Route::get('brand/{id}','\Themes\Storefront\Http\Controllers\Api\BrandController@brand');
Route::get('brand/{id}/products','\Themes\Storefront\Http\Controllers\Api\BrandController@products');

Route::get('categories','\Themes\Storefront\Http\Controllers\Api\CategoryController@index');
Route::get('category/{id}','\Themes\Storefront\Http\Controllers\Api\CategoryController@category');
Route::get('categories/parents','\Themes\Storefront\Http\Controllers\Api\CategoryController@parentOnly');
Route::get('category/childs/{parent_id}','\Themes\Storefront\Http\Controllers\Api\CategoryController@childs');

Route::get('files','\Themes\Storefront\Http\Controllers\Api\FileController@index');
Route::get('file/{id}','\Themes\Storefront\Http\Controllers\Api\FileController@file');

Route::get('reviews','\Themes\Storefront\Http\Controllers\Api\ReviewController@index');
Route::get('review/{id}','\Themes\Storefront\Http\Controllers\Api\ReviewController@review');

Route::get('coupons','\Themes\Storefront\Http\Controllers\Api\CouponController@index');
Route::get('coupon/{id}','\Themes\Storefront\Http\Controllers\Api\CouponController@coupon');
Route::get('coupon/search/{search}','\Themes\Storefront\Http\Controllers\Api\CouponController@search');

Route::get('pages','\Themes\Storefront\Http\Controllers\Api\PageController@index');
Route::get('page/{id}','\Themes\Storefront\Http\Controllers\Api\PageController@page');

Route::get('tags','\Themes\Storefront\Http\Controllers\Api\TagController@index');
Route::get('tag/{id}','\Themes\Storefront\Http\Controllers\Api\TagController@tag');

Route::get('taxs','\Themes\Storefront\Http\Controllers\Api\TaxController@index');
Route::get('tax/{id}','\Themes\Storefront\Http\Controllers\Api\TaxController@tax');

Route::get('sliders','\Themes\Storefront\Http\Controllers\Api\SliderController@index');
Route::get('slider/{id}','\Themes\Storefront\Http\Controllers\Api\SliderController@slider');

Route::post('login','\Themes\Storefront\Http\Controllers\Api\AuthenticationController@login');
Route::post('register','\Themes\Storefront\Http\Controllers\Api\AuthenticationController@register');
Route::post('resetsend','\Themes\Storefront\Http\Controllers\Api\AuthenticationController@resetPassword');
Route::post('resetpassword/{email}/{code}','\Themes\Storefront\Http\Controllers\Api\AuthenticationController@postResetComp')->name('resetpassword');

Route::get('settings','\Themes\Storefront\Http\Controllers\Api\OptionsController@index');
Route::get('settings/recently','\Themes\Storefront\Http\Controllers\Api\OptionsController@recentlyAdded');
Route::get('settings/viewed','\Themes\Storefront\Http\Controllers\Api\OptionsController@mostViewed');


// Route::get('sliders',function(){
//     $sliders = Slider::get();
//     return response()->json($sliders);
// });

// Route::get('search/{string}','\Themes\Storefront\Http\Controllers\Api\SliderController@slider');




Route::group(['middleware' => ['auth.api']],function(){
    Route::post('order/create','\Themes\Storefront\Http\Controllers\Api\OrderController@create');
    Route::get('order/data','\Themes\Storefront\Http\Controllers\Api\OrderController@dataReq');
    Route::get('profile','\Themes\Storefront\Http\Controllers\Api\ProfileController@profile');
    Route::post('wishlist/store','\Themes\Storefront\Http\Controllers\Api\ProfileController@wishlistStore');
    Route::delete('wishlist/delete/{productId}','\Themes\Storefront\Http\Controllers\Api\ProfileController@wishlistDelete');
    Route::get('profile','\Themes\Storefront\Http\Controllers\Api\ProfileController@profile');
    Route::post('profile','\Themes\Storefront\Http\Controllers\Api\ProfileController@update');
    Route::post('review/{productId}','\Themes\Storefront\Http\Controllers\Api\ReviewController@store');
    Route::post('savecoupon','\Themes\Storefront\Http\Controllers\Api\ProfileController@saveCoupon');
    Route::delete('deletecoupon/{key}','\Themes\Storefront\Http\Controllers\Api\ProfileController@deleteCoupon');

    
    Route::get('user/reviews','\Themes\Storefront\Http\Controllers\Api\ProfileController@userReviews');
    Route::get('user/orders','\Themes\Storefront\Http\Controllers\Api\ProfileController@userOrders');
    Route::get('user/order/{id}','\Themes\Storefront\Http\Controllers\Api\ProfileController@userOrdersSingle');
    Route::post('user/coupons','\Themes\Storefront\Http\Controllers\Api\ProfileController@userCoupons');
    
    
});

Route::post('loginsocial','\Themes\Storefront\Http\Controllers\Api\AuthenticationController@socialLogin');


Route::fallback(function(){

    return response()->json(['message' => 'Not Found.'], 404);

})->name('api.fallback.404');