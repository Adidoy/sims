<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInspectionsSuppliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inspections_supplies', function (Blueprint $table) {
            $table->integer('inspection_id')->unsigned();
            $table->foreign('inspection_id')
                    ->references('id')
                    ->on('inspections');        
            $table->integer('supply_id')->unsigned();
            $table->foreign('supply_id')
                    ->references('id')
                    ->on('supplies');
            $table->integer('quantity_passed');
            $table->integer('quantity_failed')->nullable();
            $table->string('comment');
            $table->primary(['inspection_id', 'supply_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inspections_supplies');
    }
}
