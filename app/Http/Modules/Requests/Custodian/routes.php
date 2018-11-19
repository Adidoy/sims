<?php

namespace App\Http\Modules\Requests\Custodian;

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
        Route::middleware(['amo'])->namespace('requests\custodian')->group(function() {
            Route::prefix('request/custodian')->group(function() {
                Route::get('/', 'RequestsCustodianController@index');
                Route::get('{id}', 'RequestsCustodianController@show');
                Route::get('{id}/cancel', 'RequestsCustodianController@getCancelRequest');
                Route::post('{id}/cancel', [
                    'as' => 'request.cancel',
                    'uses' => 'RequestsCustodianController@cancelRequest'
                ]);
                Route::get('{id}/print', 'RequestsCustodianController@printRIS');
            });
        });
    }
}