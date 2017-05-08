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

            $table->string('resultat');
            $table->integer('winner');
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
