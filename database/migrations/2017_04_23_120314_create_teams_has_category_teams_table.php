<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeamsHasCategoryTeamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('category_teams_team', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('team_id')->unsigned()->index();
            $table->foreign('team_id')
                ->references('id')
                ->on('teams')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->integer('category_teams_id')->unsigned()->index();    
            $table->foreign('category_teams_id')
                ->references('id')
                ->on('category_teams')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('category_teams_team');
    }
}
