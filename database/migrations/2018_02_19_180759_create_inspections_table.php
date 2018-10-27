<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInspectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inspections', function (Blueprint $table) {
            $table->increments('id');
            $table->string('local');
            $table->string('inspection_personnel');
            $table->dateTime('inspection_date');
            $table->string('remarks');
            $table->string('inspection_approval')->nullable();
            $table->dateTime('inspection_approval_date')->nullable();
            $table->string('property_custodian_acknowledgement')->nullable();
            $table->dateTime('property_custodian_acknowledgement_date')->nullable();
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
        Schema::dropIfExists('inspections');
    }
}
