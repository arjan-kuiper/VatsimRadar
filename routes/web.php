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

Route::get('/', 'HomeController@index');
Route::get('/faq', function(){
   return view('pages/faq');
});
Route::get('/changelog', function(){
    return view('pages/changelog');
});
/*route::get('/features', function(){
   return view('pages/features');
});*/