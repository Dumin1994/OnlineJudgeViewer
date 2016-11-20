<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$app->get('/', ['as' => 'home', function () use ($app) {
    return view('home');
}]);

// Test Route (Only debug)
$app->get('test', 'TestController@index');

$app->get('error', ['as' => 'error', function () {
    //
}]);

// User Route
$app->get('user', ['as' => 'user-index', 'uses' => 'UserController@index']);
$app->get('user/{id}', ['as' => 'user-query', 'uses' => 'UserController@query']);
$app->get('user/{id1}/{id2}', ['as' => 'user-compare', 'uses' => 'UserController@compare']);

// Admin
$app->get('admin', ['as' => 'auth', function () {
    return view('admin.auth.login');
}]);
$app->group(['prefix' => 'admin/{AccessKey}', 'middleware' => 'auth', 'namespace' => '\App\Http\Controllers'], function () use ($app) {
    $app->get('/', ['as' => 'admin-index', 'uses' => 'AdminController@index']);
    // Class RESTful Route
    $app->get('/class', ['as' => 'class-index', 'uses' => 'ClassController@index']);
    $app->get('/class/create', ['as' => 'class-create', 'uses' => 'ClassController@create']);
    $app->post('/class', ['as' => 'class-store', 'uses' => 'ClassController@store']);
    $app->get('/class/{id}', ['as' => 'class-show', 'uses' => 'ClassController@show']);
    $app->get('/class/{id}/edit', ['as' => 'class-edit', 'uses' => 'ClassController@edit']);
    $app->patch('/class/{id}', ['as' => 'class-update', 'uses' => 'ClassController@update']);
    $app->delete('/class/{id}', ['as' => 'class-destroy', 'uses' => 'ClassController@destroy']);
});

// API
$app->group(['prefix' => 'api', 'namespace' => '\App\Http\Controllers'], function () use ($app) {
    // info
    $app->get('info/user/{id}', ['as' => 'api-user-data', 'uses' => 'ApiInfoController@user']);
    $app->get('info/user/{id1}/{id2}', ['as' => 'api-user-compare', 'uses' => 'ApiInfoController@compareUser']);
    $app->get('info/class/{id}', ['as' => 'api-class', 'uses' => 'ApiInfoController@getClass']);
    $app->get('info/summary', ['as' => 'api-summary', 'uses' => 'ApiInfoController@summary']);
    // update
    $app->get('update/{ACCESS_KEY}/{id}', ['as' => 'api-update-all', 'uses' => 'ApiUpdateController@update']);
});