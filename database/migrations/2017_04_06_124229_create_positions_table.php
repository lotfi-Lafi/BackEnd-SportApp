<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePositionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('positions', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('client_id')->unsigned()->nullable();
            $table->foreign('client_id')
                ->references('id')
                ->on('clients')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->integer('goalKeeper');
            $table->integer('defender');
            $table->integer('middlefield');
            $table->integer('striker');

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
        Schema::dropIfExists('positions');
    }
}
