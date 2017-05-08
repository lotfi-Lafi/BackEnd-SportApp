<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHalfTimesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('half_times', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('match_id')->unsigned()->index();    
            $table->foreign('match_id')
                ->references('id')
                ->on('matches')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->string('resultat');
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
        Schema::dropIfExists('half_times');
    }
}
