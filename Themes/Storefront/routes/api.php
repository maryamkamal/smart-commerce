<?php

use Illuminate\Support\Facades\Route;



// Route::group(['middleware' => 'auth:api'],function(){



// });

Route::fallback(function(){

    return response()->json(['message' => 'Not Found.'], 404);

})->name('api.fallback.404');

/* -------- Routes ------------- Controllers ---------------------------------------- Methods */



//Route::get('product/{id}/{api_token}','\Themes\Storefront\Http\Controllers\Api\ProductController@getproduct');
//Route::get('product/{id}','\Themes\Storefront\Http\Controllers\Api\ProductController@product');
//Route::get('product/{id}','\Themes\Storefront\Http\Controllers\Api\ProductController@product');
Route::get('product/reviews/{id}','\Themes\Storefront\Http\Controllers\Api\ProductController@reviews');
Route::get('product/related/{id}','\Themes\Storefront\Http\Controllers\Api\ProductController@getRelatedProducts');
Route::get('product/upsell/{id}','\Themes\Storefront\Http\Controllers\Api\ProductController@getUpSellProducts');
Route::get('product/cross/{id}','\Themes\Storefront\Http\Controllers\Api\ProductController@getCrossSellProducts');
//Route::get('product/{id}/relatedProducts','\Themes\Storefront\Http\Controllers\Api\ProductController@relatedProducts');
Route::post('product/search','\Themes\Storefront\Http\Controllers\Api\ProductController@search');
Route::get('brands','\Themes\Storefront\Http\Controllers\Api\BrandController@index');
Route::get('brand/{id}','\Themes\Storefront\Http\Controllers\Api\BrandController@brand');
Route::post('brand/{id}/products','\Themes\Storefront\Http\Controllers\Api\BrandController@products');
Route::post('cart','\Themes\Storefront\Http\Controllers\Api\ProductController@cartProduct');

Route::get('category/{id}','\Themes\Storefront\Http\Controllers\Api\CategoryController@category');
Route::post('category/{id}/products','\Themes\Storefront\Http\Controllers\Api\CategoryController@categoryProducts');
Route::post('childcategory/{parent_id}/products','\Themes\Storefront\Http\Controllers\Api\CategoryController@childsProducts');

Route::get('categories/parents','\Themes\Storefront\Http\Controllers\Api\CategoryController@parentOnly');
Route::get('category/childs/{parent_id}','\Themes\Storefront\Http\Controllers\Api\CategoryController@childs');
Route::get('files','\Themes\Storefront\Http\Controllers\Api\FileController@index');
Route::get('file/{id}','\Themes\Storefront\Http\Controllers\Api\FileController@file');

Route::get('category/attributes/{category_id}','\Themes\Storefront\Http\Controllers\Api\AttributeController@categoryAttributes');
Route::get('attributes/products','\Themes\Storefront\Http\Controllers\Api\AttributeController@productsAttributes');

Route::get('coupons','\Themes\Storefront\Http\Controllers\Api\CouponController@index');
Route::get('coupon/{code}','\Themes\Storefront\Http\Controllers\Api\CouponController@coupon');
Route::get('coupon/products/{id}','\Themes\Storefront\Http\Controllers\Api\CouponController@products');
Route::get('coupon/categories/{id}','\Themes\Storefront\Http\Controllers\Api\CouponController@categories');
Route::get('shippingcost','\Themes\Storefront\Http\Controllers\Api\CouponController@shippingCost');

Route::get('pages','\Themes\Storefront\Http\Controllers\Api\PageController@index');
Route::get('page/{id}','\Themes\Storefront\Http\Controllers\Api\PageController@page');

Route::get('tags','\Themes\Storefront\Http\Controllers\Api\TagController@index');
Route::get('tag/{id}','\Themes\Storefront\Http\Controllers\Api\TagController@tag');

Route::get('taxs','\Themes\Storefront\Http\Controllers\Api\TaxController@index');
Route::get('tax/{id}','\Themes\Storefront\Http\Controllers\Api\TaxController@tax');

Route::get('sliders','\Themes\Storefront\Http\Controllers\Api\SliderController@index');
Route::get('slider/{id}','\Themes\Storefront\Http\Controllers\Api\SliderController@slider');
/** home page apis**/
Route::get('logo','\Themes\Storefront\Http\Controllers\Api\HomepageController@getLogo');
Route::get('twobanners','\Themes\Storefront\Http\Controllers\Api\HomepageController@getTwoBanners');
Route::get('brandslogos','\Themes\Storefront\Http\Controllers\Api\HomepageController@getBrandsLogos');
Route::get('features','\Themes\Storefront\Http\Controllers\Api\FeatureController@features');
Route::get('features/categories','\Themes\Storefront\Http\Controllers\Api\FeatureController@categories'); 
/*************************************/
Route::get('flashsale/products','\Themes\Storefront\Http\Controllers\Api\FlashSaleProductController@index');

Route::post('adminlogin','\Themes\Storefront\Http\Controllers\Api\AuthenticationController@adminlogin');
Route::post('login','\Themes\Storefront\Http\Controllers\Api\AuthenticationController@login');
Route::post('register','\Themes\Storefront\Http\Controllers\Api\AuthenticationController@register');
Route::post('resetsend','\Themes\Storefront\Http\Controllers\Api\AuthenticationController@resetPassword');
Route::post('resetpassword/{email}/{code}','\Themes\Storefront\Http\Controllers\Api\AuthenticationController@postResetComp')->name('resetpassword');
Route::post('sociallogin','\Themes\Storefront\Http\Controllers\Api\AuthenticationController@sociallogin');
Route::post('resetcode','\Themes\Storefront\Http\Controllers\Api\AuthenticationController@resetcode');
Route::post('postreset','\Themes\Storefront\Http\Controllers\Api\AuthenticationController@postReset');


    Route::get('order/{id}/products','\Themes\Storefront\Http\Controllers\Api\OrderController@products');
    Route::get('orders','\Themes\Storefront\Http\Controllers\Api\OrderController@orders');
    Route::post('order/{id}/statusupdate','\Themes\Storefront\Http\Controllers\Api\OrderController@statusUpdate');
    Route::post('order/store','\Themes\Storefront\Http\Controllers\Api\OrderController@store');
    Route::post('profile','\Themes\Storefront\Http\Controllers\Api\ProfileController@profile');
    Route::post('user/reviews','\Themes\Storefront\Http\Controllers\Api\ProfileController@userReviews');
    Route::post('user/orders','\Themes\Storefront\Http\Controllers\Api\ProfileController@userorders');
    Route::get('order/{id}/products','\Themes\Storefront\Http\Controllers\Api\ProductController@orederProduct');
    Route::get('review/{productid}','\Themes\Storefront\Http\Controllers\Api\ReviewController@review');
    Route::get('wishlist','\Themes\Storefront\Http\Controllers\Api\ProfileController@wishlist');
    Route::post('wishlist/store','\Themes\Storefront\Http\Controllers\Api\ProfileController@wishlistStore');
    Route::post('wishlist/delete','\Themes\Storefront\Http\Controllers\Api\ProfileController@wishlistDelete');
    Route::post('profile/update','\Themes\Storefront\Http\Controllers\Api\ProfileController@update');
    Route::post('review/store/{productId}','\Themes\Storefront\Http\Controllers\Api\ReviewController@store');
	Route::post('mobiletoken','\Themes\Storefront\Http\Controllers\Api\TokenController@storetokens');
    Route::post('payment','\Themes\Storefront\Http\Controllers\Api\PaymentController@mobilepayment'); 
/************** notifications ******************/
   Route::get('notifications','\Themes\Storefront\Http\Controllers\Api\NotificationController@index');
   Route::post('notification','\Themes\Storefront\Http\Controllers\Api\NotificationController@sendNotification'); 




