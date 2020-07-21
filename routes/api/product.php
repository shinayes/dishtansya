<?php

/*
|--------------------------------------------------------------------------
| Product Routes
|--------------------------------------------------------------------------
|
| Here is where you can register api routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "api" middleware group. Now create something great!
|
*/

$router->group(['prefix' => 'product'], function ($router) {
    $router->group(['middleware' => ['auth:api']], function ($router) {
        $router->get('', [
            'uses' => 'ProductController@index',
            'name' => 'product_list',
            'description' => 'Get all products.'
        ]);

        $router->get('/get/{id}', [
            'uses' => 'ProductController@show',
            'name' => 'product_specific_record',
            'description' => 'Get product record by id.'
        ]);

        $router->post('/create', [
            'uses' => 'ProductController@create',
            'name' => 'product_create_record',
            'description' => 'Create product record.'
        ]);

        $router->post('/update', [
            'uses' => 'ProductController@update',
            'name' => 'product_update_record',
            'description' => 'Update product record.'
        ]);

        $router->post('/order', [
            'uses' => 'ProductController@order',
            'name' => 'product_order',
            'description' => 'Order product.'
        ]);

        $router->delete('/archive/{id}', [
            'uses' => 'ProductController@destroy',
            'name' => 'product_delete_record',
            'description' => 'Delete product record'
        ]);
    });
});
