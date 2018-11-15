<?php

namespace App\Http\Modules\Requests\Client;

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
        Route::middleware(['offices'])->namespace('requests\client')->group(function() {
            Route::prefix('request/client')->group(function() {
                Route::get('create', 'RequestsClientController@create');
                Route::post('create', 'RequestsClientController@store');
                Route::get('/', 'RequestsClientController@index');
                Route::get('{id}', 'RequestsClientController@show');
                Route::get('{id}/cancel', 'RequestsClientController@getCancelRequest');
                Route::post('{id}/cancel', [
                    'as' => 'request.cancel',
                    'uses' => 'RequestClientController@cancelRequest'
                ]);
            });
        });
    }
}