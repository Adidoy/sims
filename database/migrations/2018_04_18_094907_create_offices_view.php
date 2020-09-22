<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOfficesView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $query = DB::select("SELECT * FROM information_schema.VIEWS WHERE TABLE_NAME='offices_v'");
        if(empty($query)){
            DB::statement("
                CREATE VIEW offices_v AS
                SELECT o1.id AS 'office_id',
                       IFNULL(o4.id,IFNULL(o3.id,IFNULL(o2.id,o1.id))) AS 'sector_id',
                       o1.code AS 'level1',
                       IFNULL(o2.code,o1.code) AS 'level2',
                       IFNULL(o3.code,IFNULL(o2.code,o1.code)) AS 'level3',
                       IFNULL(o4.code,IFNULL(o3.code,IFNULL(o2.code,o1.code))) AS 'level4'
                FROM offices AS o1
                LEFT JOIN offices AS o2 ON o1.head_office = o2.id
                LEFT JOIN offices AS o3 ON o2.head_office = o3.id
                LEFT JOIN offices AS o4 ON o3.head_office = o4.id
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
    DB::statement("drop view if exists offices_v");
    }
}
