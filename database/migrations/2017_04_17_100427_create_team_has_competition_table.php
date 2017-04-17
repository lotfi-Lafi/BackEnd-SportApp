<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeamHasCompetitionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('team_has_competition', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('team_id')->unsigned()->index();
            $table->foreign('team_id')
                ->references('id')
                ->on('teams')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->integer('competition_id')->unsigned()->index();    
            $table->foreign('competition_id')
                ->references('id')
                ->on('competitions')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->integer('status');
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
        Schema::drop('team_has_competition');
    }
}
