<?php

use Illuminate\Http\Request;
use App\opotion;
use Tymon\JWTAuth\Contracts\JWTSubject;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
/*to use swt ,add below middleware to route middleware
'auth:api'*/

Route::group(['prefix' => 'v1', 'namespace' => 'Api\v1', 'middleware' => 'APP_KEY'], function () {

    // Custom API
    Route::post('updateFinancial', 'FinancialController@update_user_financial');
    Route::group(['prefix' => 'contract'], function () {
        Route::post('start', 'ContractsController@start_contract');
        Route::post('revival', 'ContractsController@revival_contract');

        Route::group(['prefix' => 'seller'], function () {
            Route::post('cancel', 'ContractsController@cancel_contract_by_seller');
            Route::post('checkout', 'ContractsController@checkout_seller');
        });

        Route::group(['prefix' => 'customer'], function () {
            Route::post('cancel', 'ContractsController@cancel_contract_by_customer');
            Route::post('payment', 'ContractsController@payment');
            Route::post('token', 'ContractsController@send_token');
        });

    });


    // Global API
    Route::post('register', 'authAdminsController@register');
    Route::post('otp', 'authAdminsController@otp');
    Route::post('completeLogin_with_google2fa', 'authAdminsController@completeLogin_with_google2fa');
    Route::group(['auth.jwt'], function () {
        Route::post('login', 'authAdminsController@login');


        Route::get('google2fa', 'authAdminsController@google2faLogin');

        Route::group(['prefix' => 'mediaManagment'], function () {
            Route::post('store', 'mediaController@store')->name('storeMedia');
            Route::post('delete', 'mediaController@delete');
            Route::post('show', 'mediaController@show');
            Route::post('edit', 'mediaController@edit');
        });

        Route::group(['prefix' => 'options'], function () {
            Route::post('create', 'optionsController@create'); // value must be an array
            Route::post('edit/{id}', 'optionsController@edit'); // value must be an array
            Route::post('show/{id}', 'optionsController@show');
        });

        Route::post('getPayload', 'jwtController@getPayload');
        Route::post('unsetJwt', 'jwtController@unsetJwt');

        Route::group(['prefix' => 'query'], function () {
            Route::post('QueryBuilder', 'QueryController@QueryBuilder');

        });
    });

});
Route::post('v1/productCard','Api\v1\productController@productCard');


