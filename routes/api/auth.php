<?php

/*
|--------------------------------------------------------------------------
| Authentication Route
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "api" middleware group. Now create something great!
|
*/

$router->post('/register', [
    'as' => 'register',
    'uses' => 'AuthController@register',
    'name' => 'register',
    'description' => 'App register route'
]);

$router->group([
    'prefix' => 'auth'
], function ($router) {
    $router->post('/login', [
        'as' => 'login',
        'uses' => 'AuthController@login',
        'name' => 'login',
        'description' => 'App login route'
    ]);

    $router->group(['middleware' => 'auth:api'], function ($router) {
        $router->delete('/logout', [
            'uses' => 'AuthController@logout',
            'name' => 'logout',
            'description' => 'App logout route'
        ]);
    });
});
