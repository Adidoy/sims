<?php

namespace App\Http\Modules\Adjustments;

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
        Route::middleware(['supplies-chief'])->namespace('inventory\adjustments')->group(function() {
            Route::prefix('inventory/adjustments')->group(function() {
                Route::get('/', 'AdjustmentsController@index');
                Route::post('receive/create',[
		            'as' => 'adjustment.receive.create',
			        'uses' => 'AdjustmentsController@receive'
                ]);
                Route::post('issue/create',[
		            'as' => 'adjustment.issue.create',
			        'uses' => 'AdjustmentsController@issue'
		        ]);
                Route::get('receive/create','AdjustmentsController@create');
                Route::get('issue/create','AdjustmentsController@createIssue');
		        Route::get('{id}', 'AdjustmentsController@show');
		        //Route::get('reports{id}/print', 'DeliveryController@print');
            });
        });
    }
}