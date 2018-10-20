<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeliveryDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('deliveries_details', function (Blueprint $table) {
            $table->integer('delivery_id')->unsigned();
            $table->foreign('delivery_id')->references('id')->on('deliveries_header');
            $table->string('stocknumber');
            $table->integer('quantity_delivered');
            $table->decimal('unit_cost',8,2);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('deliveries_details');
    }
}
