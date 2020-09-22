<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('settings')){
            Schema::create('settings', function (Blueprint $table) {
                $table->increments('id');
                $table->string('key');
                $table->string('name');
                $table->string('description')->nullable();
                $table->text('value')->nullable();
                $table->text('field');
                $table->tinyInteger('active');
                $table->timestamps();
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
        Schema::drop('settings');
    }
}
