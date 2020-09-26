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

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::group(['prefix' => 'v1', 'namespace' => 'Api\v1' ], function () {
    Route::group(['prefix' => 'session'], function () {
        Route::group(['auth.jwt'], function () {
            Route::post('sessionController', 'sessionsController@sessionController')->name('session');
        });
    });
});


//admin
Route::get('/panel', 'admin\dashboardController@loginPage');
Route::get('adminLogin', 'Api\v1\authAdminsController@adminLogin')->name('login');
Route::group(['prefix' => 'panel' , 'middleware'=>'auth'], function () {

    Route::get('/main', 'admin\dashboardController@mainPage')->name('main');
    Route::get('/logout', 'admin\dashboardController@logout')->name('logout');

    Route::group(['prefix' => 'adminsInfo'], function () {
        Route::get('/', 'admin\adminsInfoController@index')->name('adminsInfo');
        Route::get('/addAdmin', 'admin\adminsInfoController@addAdmin')->name('addAdmin');
        Route::get('/editPage/{id}', 'admin\adminsInfoController@editPage')->name('editPage');
        Route::post('/editAdmin/{id}', 'admin\adminsInfoController@editAdmin')->name('editAdmin');
        Route::get('/deleteAdmin/{id}', 'admin\adminsInfoController@deleteAdmin')->name('deleteAdmin');
    });

    Route::group(['prefix'=>'users'],function (){
        Route::get('/', 'admin\userController@index')->name('users');
        Route::get('/userInfo/{id}', 'admin\userController@userInfo')->name('userInfo');
        Route::get('/deleteUser/{id}', 'admin\userController@deleteUser')->name('deleteUser');
        //Route::get('/confirmMedia/{id}', 'admin\userController@confirmMedia')->name('confirmMedia');
        Route::get('/unconfirmMedia/{id}/{status}', 'admin\userController@unconfirmMedia')->name("unconfirmMedia");
    });


    Route::group(['prefix' => 'product'], function () {
        Route::get('/', 'admin\productController@index')->name('product');
        Route::get('/productInfo/{id}', 'admin\productController@productInfo')->name('productInfo');
        Route::post('/addProduct', 'admin\productController@addProduct')->name('addProduct');
        Route::post('/editProduct/{id}', 'admin\productController@editProduct')->name('editProduct');
        Route::get('/deleteProduct/{id}', 'admin\productController@deleteProduct')->name('deleteProduct');
        Route::post('/uploadMainMedia/{id}', 'admin\productController@uploadMainMedia')->name('uploadMainMedia');
    });

    Route::group(['prefix'=>'membership'],function (){
        Route::get('/create','admin\memberShipController@create')->name('createMembership');
        Route::get('/delete/{id}','admin\memberShipController@delete')->name('deleteMemberShip');
        Route::get('/edit/{id}','admin\memberShipController@edit')->name('editMemberShip');
    });
});



