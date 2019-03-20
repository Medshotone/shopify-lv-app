<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


Route::get('/install', 'InstallController@index');

Route::post('install/', [
    'as' => 'install', 'uses' => 'InstallController@creat_user'
]);

Route::match(['get', 'post'], 'auth',[
    'as' => 'install', 'uses' => 'AuthController@creat_user_token'
]);

Route::get('/customize_product/', 'CustomFieldsController@index');

Route::post('customize_product/update', [
    'as' => 'customize_product/update', 'uses' => 'CustomFieldsController@metafields_update'
]);
Route::post('customize_product/delete', [
    'as' => 'customize_product/delete', 'uses' => 'CustomFieldsController@metafield_delete'
]);
