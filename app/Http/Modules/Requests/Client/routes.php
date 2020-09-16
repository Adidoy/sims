<?php

namespace App\Http\Modules\Requests\Client;

use Illuminate\Support\Facades\Route;
use App\Reports\bin\PUPSIMSReporting;

class Routes
{

    /**
     * Return all the routes available to this class
     *
     * @return void
     */
    public static function all()
    {
        Route::middleware(['offices'])->namespace('requests\client')->group(function() {
            Route::prefix('request/client')->group(function() {
                Route::get('create', 'RequestsClientController@create');
                Route::post('create', 'RequestsClientController@store');
                Route::get('/pending', 'RequestsClientController@pendingRequests');
                Route::get('/approved', 'RequestsClientController@approvedRequests');
                Route::get('/released', 'RequestsClientController@releasedRequests');
                Route::get('/disapproved', 'RequestsClientController@disapprovedRequests');
                Route::get('{id}', 'RequestsClientController@show');
                Route::get('{id}/cancel', 'RequestsClientController@getCancelRequest');
                Route::post('{id}/cancel', [
                    'as' => 'cancel.request',
                    'uses' => 'RequestsClientController@cancelRequest'
                ]);
                Route::get('{id}/print', 'RequestsClientController@printRIS');
            });
        });
    }
}