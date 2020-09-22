<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCorrectionItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('correction_items')){
            Schema::create('correction_items', function (Blueprint $table) {
                $table->integer('correction_id')->unsigned();
                $table->foreign('correction_id')
                        ->references('id')
                        ->on('corrections');        
                $table->integer('supply_id')->unsigned();
                $table->foreign('supply_id')
                        ->references('id')
                        ->on('supplies');
                $table->integer('quantity_from');
                $table->integer('quantity_to');
                $table->string('remarks');
                $table->string('status');
                $table->primary(['correction_id', 'supply_id']);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('correction_items');
    }
}
