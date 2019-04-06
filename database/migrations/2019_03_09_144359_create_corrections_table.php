<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCorrectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('corrections', function (Blueprint $table) {
            $table->increments('id');
            $table->string('control_number');
            $table->datetime('filled_at')->nullable();
            $table->datetime('submitted_at')->nullable();
            $table->datetime('processed_at')->nullable();
            $table->longText('remarks')->nullable();
            $table->longText('reasons')->nullable();
            $table->string('requested_by')->nullable();
            $table->string('processed_by')->nullable();
            $table->string('endorsed_by')->nullable();
            $table->string('status')->nullable();
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
        Schema::dropIfExists('corrections');
    }
}
