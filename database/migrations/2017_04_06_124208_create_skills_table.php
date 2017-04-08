<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSkillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('skills', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('client_id')->unsigned()->nullable();
            $table->foreign('client_id')
                ->references('id')
                ->on('clients')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->integer('speed');
            $table->integer('endurance');
            $table->integer('shoot');
            $table->integer('dribble');
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
        Schema::dropIfExists('skills');
    }
}
