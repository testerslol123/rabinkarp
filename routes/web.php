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

Route::get('aboutus', 'HomeController@aboutus');

Route::get('login', function() {
    return view('login');
});

Route::get('user', function() {
    return view('user');
});

Route::get('register', function() {
    return view('register');
});

Route::post('document', 'HomeController@submitDocument');
Route::post('document2', 'HomeController@submit2');

Route::get('dashboard', 'AdminController@index');
Route::get('edituser/{user_id}', 'AdminController@edituser');
Route::post('edituser', 'AdminController@submitEditUser');
Route::get('deleteuser/{user_id}', 'AdminController@deleteUser');



Route::get('testdocument', 'HomeController@testdocument');
Route::get('testdocument2', 'HomeController@testdocument2');
Route::get('testdocument3', 'HomeController@testdocument3');


Auth::routes();


