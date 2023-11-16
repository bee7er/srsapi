<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class AddCancelledRendersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // NB Not sure if this is correct way of adding an enum value
        // ALTER TABLE `render_details` CHANGE `status` `status` ENUM('ready','allocated','done','returned','cancelled') NOT NULL;
        Schema::table('render_details', function ($table) {
            $table->enum('status', array('ready','allocated','done','returned','cancelled'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('render_details', function (Blueprint $table) {
            $table->enum('status', array('ready','allocated','done','returned'));
        });
    }
}
