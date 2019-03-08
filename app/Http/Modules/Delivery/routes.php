<?php

namespace App\Http\Modules\Delivery;

use Illuminate\Support\Facades\Route;

class Routes
{

    /**
     * Return all the routes available to this class
     *
     * @return void
     */
    public static function all()
    {
        Route::middleware(['supplies-chief'])->namespace('delivery')->group(function() {
            Route::prefix('delivery/supplies')->group(function() {
                Route::get('/', 'DeliveryController@index');
                Route::post('create',[
		            'as' => 'delivery.supply.create',
			        'uses' => 'DeliveryController@store'
		        ]);
		        Route::get('create','DeliveryController@create');
		        Route::get('{id}', 'DeliveryController@show');
		        Route::get('{id}/print', 'DeliveryController@print');
            });
        });
    }
}