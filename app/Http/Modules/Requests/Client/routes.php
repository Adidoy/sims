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
            
            Route::resource('request','RequestsClientController');
            //Route::prefix('request')->group(function() {
                
                // Route::get('supply/print','SupplyController@print');
                // Route::resource('supply','SupplyController');
                // Route::resource('office','OfficeController');
                // Route::resource('unit','UnitsController');
                // Route::resource('supplier','SuppliersController');
                // Route::resource('department','DepartmentController');
                // Route::resource('category','CategoryController');

                // Route::prefix('category')->namespace('category')->group(function() {
                //     Route::get('assign/{id}', 'AssignmentController@show');
                //     Route::put('assign/{id}', 'AssignmentController@store');
                //});
            });
        //});
    }
}