<?php

namespace App\Http\Modules\Inspection;

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
        Route::middleware(['inspection-team'])->namespace('inspection')->group(function() {
            Route::prefix('inspection/supplies')->group(function() {
                Route::get('/', 'InspectionController@index');
                Route::get('view/', 'InspectionController@showInspected');
                Route::get('{id}/', 'InspectionController@show');
                Route::get('view/{id?}', 'InspectionController@showInspected');
                Route::post('accept/',[
                    'as' => 'inspection.accept',
                    'uses'=>'InspectionController@store'
                ]);
                Route::post('{id}/action={action}/',[
                    'as' => 'inspection.approve',
                    'uses'=>'InspectionController@approveInspection'
                ]);
                Route::get('{id}/print', 'InspectionController@print');
            });
        });
    }
}