<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('matches', function (Blueprint $table) {
            
            $table->increments('id');

            $table->integer('competition_id')->unsigned()->index();    
            $table->foreign('competition_id')
                ->references('id')
                ->on('competitions')
                ->onUpdate('cascade')
                ->onDelete('cascade');
                
            $table->integer('teamOne');
            $table->integer('teamTwo');
            $table->integer('resultatTeamOne')->nullable();
            $table->integer('resultatTeamTwo')->nullable();
            $table->integer('winner')->nullable();
            $table->string('code');
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
        Schema::dropIfExists('matches');
    }
}
