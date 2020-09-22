<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeliveriesViewSimple extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $query = DB::select("SELECT * FROM information_schema.VIEWS WHERE TABLE_NAME='vDeliveries'");
        if(empty($query)){
            DB::STATEMENT("
                CREATE VIEW vDeliveries AS
                    SELECT  *
                    FROM vDeliverySummary
                    ORDER BY created_at DESC
            ");
	    } 
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
