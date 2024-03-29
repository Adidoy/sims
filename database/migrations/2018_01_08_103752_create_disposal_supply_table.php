<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDisposalSupplyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('disposal_supply')){
            Schema::create('disposal_supply', function(Blueprint $table){
                $table->increments('id');
                $table->integer('disposal_id')->unsigned();
                $table->foreign('disposal_id')
                        ->references('id')
                        ->on('disposals')
                        ->onUpdate('cascade')
                        ->onDelete('cascade');        
                $table->integer('supply_id')->unsigned();
                $table->foreign('supply_id')
                        ->references('id')
                        ->on('supplies')
                        ->onUpdate('cascade')
                        ->onDelete('cascade');
                $table->integer('quantity');
                $table->decimal('unitcost', 8, 2)->nullable();
                $table->softDeletes();
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
        Schema::dropIfExists('disposal_supply');
    }
}
