<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeliveryHeaderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deliveries_header', function (Blueprint $table) {
            $table->increments('id');
            $table->string('local');
            $table->integer('supplier_id')->unsigned()->nullable();
            $table->foreign('supplier_id')
                    ->references('id')
                    ->on('suppliers')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');
            $table->integer('fund_source')->unsigned()->nullable();
            $table->foreign('fund_source')
                    ->references('id')
                    ->on('fundclusters')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');
            $table->string('purchaseorder_no');
            $table->datetime('purchaseorder_date');
            $table->string('invoice_no');
            $table->datetime('invoice_date');
            $table->string('delrcpt_no');
            $table->datetime('delivery_date');
            $table->integer('received_by')->unsigned();
            $table->foreign('received_by')
                    ->references('id')
                    ->on('users');
            $table->softDeletes();
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
        Schema::dropIfExists('deliveries_header');
    }
}
