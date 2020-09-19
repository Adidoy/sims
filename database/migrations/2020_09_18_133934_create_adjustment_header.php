<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdjustmentHeader extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('adjustments_header', function (Blueprint $table) {
            $table->increments('id');
            $table->string('local');
            $table->string('reference');
            $table->string('reasonLeadingTo');
            $table->string('details')->nullable();
            $table->string('action');
            $table->integer('processed_by')->unsigned();
            $table->foreign('processed_by')
                    ->references('id')
                    ->on('users');
            $table->softDeletes();
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
        Schema::dropIfExists('adjustments_header');
    }
}
