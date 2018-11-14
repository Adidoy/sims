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
            //Route::resource('request','RequestsClientController');
            Route::prefix('request')->group(function() {
                Route::get('create', 'RequestsClientController@create');
                Route::post('create', [
                    'as'=> 'request.store',
                    'uses' => 'RequestsClientController@store'
                ]);
                Route::get('pending', 'RequestsClientController@index');
                Route::get('approved', 'RequestsClientController@index');
                Route::get('released', 'RequestsClientController@index');
                Route::get('disapproved', 'RequestsClientController@index');
                Route::get('{id}', 'RequestsClientController@show');
                });
        });
    }
}