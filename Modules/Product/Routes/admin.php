<?php

use Illuminate\Support\Facades\Route;

Route::get('products', [
    'as' => 'admin.products.index',
    'uses' => 'ProductController@index',
    'middleware' => 'can:admin.products.index',
]);

Route::get('products/create', [
    'as' => 'admin.products.create',
    'uses' => 'ProductController@create',
    'middleware' => 'can:admin.products.create',
]);

Route::post('products', [
    'as' => 'admin.products.store',
    'uses' => 'ProductController@store',
    'middleware' => 'can:admin.products.create',
]);

Route::get('products/{id}/edit', [
    'as' => 'admin.products.edit',
    'uses' => 'ProductController@edit',
    'middleware' => 'can:admin.products.edit',
]);

Route::put('products/{id}', [
    'as' => 'admin.products.update',
    'uses' => 'ProductController@update',
    'middleware' => 'can:admin.products.edit',
]);

Route::delete('products/{ids}', [
    'as' => 'admin.products.destroy',
    'uses' => 'ProductController@destroy',
    'middleware' => 'can:admin.products.destroy',
]);

Route::get('products/clear-cache', function() {
    Artisan::call('cache:clear');
    return "Cache is cleared";
});

// Suppliers


Route::get('suppliers', [
    'as' => 'admin.suppliers.index',
    'uses' => 'SupplierController@index',
    'middleware' => 'can:admin.suppliers.index',
]);

Route::get('supplier', [
    'as' => 'admin.supplier.index',
    'uses' => 'SupplierController@index',
    'middleware' => 'can:admin.suppliers.index',
]);

Route::get('suppliers/create', [
    'as' => 'admin.suppliers.create',
    'uses' => 'SupplierController@create',
    'middleware' => 'can:admin.suppliers.create',
]);

Route::post('suppliers', [
    'as' => 'admin.suppliers.store',
    'uses' => 'SupplierController@store',
    'middleware' => 'can:admin.suppliers.create',
]);

Route::get('suppliers/{id}/edit', [
    'as' => 'admin.suppliers.edit',
    'uses' => 'SupplierController@edit',
    'middleware' => 'can:admin.suppliers.edit',
]);

Route::put('suppliers/{id}', [
    'as' => 'admin.suppliers.update',
    'uses' => 'SupplierController@update',
    'middleware' => 'can:admin.suppliers.edit',
]);

Route::delete('suppliers/{ids?}', [
    'as' => 'admin.suppliers.destroy',
    'uses' => 'SupplierController@destroy',
    'middleware' => 'can:admin.suppliers.destroy',
]);