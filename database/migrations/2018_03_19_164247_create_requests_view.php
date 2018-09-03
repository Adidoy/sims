<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRequestsView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
        public function up()
    {
        DB::statement("
            CREATE VIEW requests_v AS
            SELECT 
            o.code,
            o.name,
            r.id as 'request_id',
            r.purpose,
            r.status,
            r.created_at,
            r.approved_at,
            r.released_at,
            s.stocknumber,
            s.details,
            u.name AS 'unit',
            rs.quantity_requested,
            rs.quantity_issued,
            rs.quantity_released
            FROM requests AS r 
            JOIN requests_supplies AS rs ON rs.request_id = r.id
            JOIN offices AS o ON r.office_id = o.id
            JOIN supplies AS s ON s.id = rs.supply_id
            JOIN units AS u ON u.id = s.unit_id
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("DROP VIEW IF EXISTS requests_v");
    }
}
