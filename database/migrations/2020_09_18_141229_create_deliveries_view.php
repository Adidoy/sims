<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeliveriesView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::STATEMENT("
            CREATE VIEW vDeliveries AS
            SELECT *
            FROM (
                SELECT  deliveries_header.id AS `ID`  
                        , deliveries_header.`local` AS `DeliveryReference`
                        , suppliers.`name` AS `Supplier`
                        , deliveries_header.`purchaseorder_no` AS `PONumber`
                        , deliveries_header.`invoice_no` AS `InvoiceNumber`
                        , deliveries_header.`delrcpt_no` AS `DRNumber`
                        , DATE_FORMAT(deliveries_header.`created_at`, '%d %M %Y %h:%m%p') AS `DateProcessed`
                        , CONCAT(users.`firstname`, ' ', users.`lastname`) AS `ProcessedBy`
                        , deliveries_header.`created_at`
                FROM deliveries_header JOIN suppliers ON suppliers.`id` = deliveries_header.`supplier_id`
                JOIN users ON deliveries_header.`received_by` = users.`id`
                UNION
                SELECT 	receipts.`id` AS `ID`
                        ,  '' AS `DeliveryReference`
                        , suppliers.`name` AS `Supplier`
                        , receipts.`number` AS `PONumber`
                        , receipts.`invoice` AS `InvoiceNumber`
                        , '' AS `DRNumber`
                        , DATE_FORMAT(receipts.`created_at`, '%d %M %Y %h:%m%p') AS `DateProcessed`
                        , '' AS `ProcessedBy`
                        , receipts.`created_at`
                FROM receipts JOIN suppliers
                ON suppliers.`id` = receipts.`supplier_id`
            ) AS Deliveries
            ORDER BY Deliveries.created_at DESC
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("drop view if exists vDeliveries");
    }
}
