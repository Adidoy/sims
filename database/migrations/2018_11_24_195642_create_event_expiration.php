<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventExpiration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::STATEMENT("
            SET GLOBAL event_scheduler = ON;

            CREATE EVENT Expiration
            ON SCHEDULE EVERY 1 HOUR
            STARTS CURRENT_TIMESTAMP
            DO UPDATE `requests`
            SET `requests`.`cancelled_at` = CURRENT_TIMESTAMP,
                `requests`.`status` = 'request expired',
                `requests`.`remarks` = 'Automatic expiration of request due to inactivity in the system.'
                WHERE `requests`.`id` IN (SELECT `request_expiration`.`request_id`
            FROM `request_expiration`
            WHERE DATE(`request_expiration`.`expiration_date`) = CURDATE());
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("drop event if exists expiration");
    }
}
