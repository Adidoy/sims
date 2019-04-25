<?php

namespace App\Http\Modules\Reports;

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
        Route::middleware(['amo'])->namespace('reports')->group(function() {
            Route::prefix('reports/')->group(function() {
                Route::get('summary/', 'ReportsController@summaryIndex');
                Route::get('summary/getRecords/{years}', 'ReportsController@getRecords');
                Route::post('summary/print/', [
                    'as'=> 'summary.submit',
                    'uses' =>'ReportsController@summaryPrint']
                );
            });
        });
    }
}