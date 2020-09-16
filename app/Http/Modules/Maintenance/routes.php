<?php

namespace App\Http\Modules\Maintenance;

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

        Route::middleware(['auth'])->namespace('maintenance')->group(function() {

            // controllers that starts with maintenance as their prefix
            // the following routes below belongs to the maintenance
            // modules of this system which can only be accessible by the
            // administrator of the system or the authorized personnels
            Route::prefix('maintenance')->group(function() {
                Route::get('supply/print','SupplyController@print');
                Route::get('get/office/autocomplete','OfficeController@showOfficeCodes');
                Route::get('get/office/{code}','OfficeController@showOfficeDetails');
                Route::resource('supply','SupplyController');
                Route::resource('office','OfficeController');
                Route::resource('unit','UnitController');
                Route::resource('supplier','SuppliersController');
                Route::resource('department','DepartmentController');
                Route::resource('category','CategoryController');

                Route::prefix('category')->namespace('category')->group(function() {
                    Route::get('assign/{id}', 'AssignmentController@show');
                    Route::put('assign/{id}', 'AssignmentController@store');
                });
            });
        });
    }
}