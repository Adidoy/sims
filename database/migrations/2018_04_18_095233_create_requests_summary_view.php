<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRequestsSummaryView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::STATEMENT("
            CREATE DEFINER=`root`@`localhost` VIEW requests_v2 AS
            SELECT 
            o.code,
            o.name,
            r.id as 'request_id',
            r.remarks,
            r.purpose,
            r.status,
            r.created_at,
            r.approved_at,
            r.released_at,
            r.cancelled_at,
            s.stocknumber,
            s.details,
            u.name AS 'unit',
            rs.quantity_requested,
            rs.quantity_issued,
            rs.quantity_released,
            requestor.firstname AS 'requestor_firstname',
            requestor.middlename AS 'requestor_middlename',
            requestor.lastname AS 'requestor_lastname',
            requestor.office AS 'requestor_office',
            requestor.position AS 'requestor_position',
            issuer.firstname AS 'issuer_firstname',
            issuer.middlename AS 'issuer_middlename',
            issuer.lastname AS 'issuer_lastname',
            issuer.office AS 'issuer_office',
            issuer.position AS 'issuer_position',
            releasor.firstname AS 'releasor_firstname',
            releasor.middlename AS 'releasor_middlename',
            releasor.lastname AS 'releasor_lastname',
            releasor.office AS 'releasor_office',
            releasor.position AS 'releasor_position'
            FROM requests AS r 
            JOIN requests_supplies AS rs ON rs.request_id = r.id
            JOIN offices AS o ON r.office_id = o.id
            JOIN supplies AS s ON s.id = rs.supply_id
            JOIN units AS u ON u.id = s.unit_id
            LEFT JOIN users AS requestor ON r.requestor_id = requestor.id
            LEFT JOIN users AS issuer ON r.issued_by = issuer.id
            LEFT JOIN users AS releasor ON r.released_by = releasor.id
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("drop view requests_v2");
    }
}
