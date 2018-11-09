<?php

namespace App\Http\Modules\Maintenance;

Illuminate\Support\Facades\Route;

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
                Route::resource('supply','SupplyController');
                Route::resource('office','OfficeController');
                Route::resource('unit','UnitsController');
                Route::resource('supplier','SuppliersController');
                Route::resource('department','DepartmentController');
                Route::resource('category','CategoryController');
                Route::get('category/assign/{id}', 'CategoriesController@showAssign');
                Route::put('category/assign/{id}', 'CategoriesController@assign');
            });
        });
    }
}