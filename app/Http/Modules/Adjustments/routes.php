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
        Route::middleware(['amo'])->namespace('Adjustments')->group(function() {
            Route::prefix('adjustment')->group(function() {
                Route::get('return', 'AdjustmentsController@returnView');
                Route::post('return', [ 'as'=>'adjustment.store', 'uses'=>'AdjustmentsController@store']);
            });
        });
    }
}