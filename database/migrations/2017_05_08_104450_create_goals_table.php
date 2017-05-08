<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGoalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goals', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('half_time_id')->unsigned()->index();    
            $table->foreign('half_time_id')
                ->references('id')
                ->on('half_times')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->date('time');
            $table->integer('player');
            $table->integer('team');
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
        Schema::dropIfExists('goals');
    }
}
