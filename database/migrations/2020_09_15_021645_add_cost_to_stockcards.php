<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCostToStockcards extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stockcards', function (Blueprint $table) 
        {
            $table->decimal('received_cost',11,2)->nullable();
            $table->decimal('issued_cost',11,2)->nullable();
            $table->decimal('balance_cost',11,2)->nullable();
            $table->decimal('total_received_cost',11,2)->nullable();
            $table->decimal('total_issued_cost',11,2)->nullable();
            $table->decimal('total_balance_cost',11,2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
