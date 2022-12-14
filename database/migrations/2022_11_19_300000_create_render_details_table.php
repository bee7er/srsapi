<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRenderDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Render detail records
        Schema::create('render_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('render_id')->unsigned()->index();
            $table->integer('allocated_to_user_id')->unsigned()->index();
            $table->integer('from');
            $table->integer('to');
            $table->enum('status', array('ready','allocated','done','returned'));
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
        Schema::drop('render_details');
    }
}
