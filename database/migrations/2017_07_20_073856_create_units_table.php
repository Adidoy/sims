<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUnitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('units')){
            Schema::create('units', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name')->unique();
                $table->string('abbreviation')->unique();
                $table->string('description');
                $table->timestamps();
                $table->softDeletes();
            });
        } 
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('units');
    }
}
