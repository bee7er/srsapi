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
        // ALTER TABLE `renders` CHANGE `status` `status` ENUM('open','ready','rendering','complete','returned','cancelled') NOT NULL;
        Schema::table('renders', function ($table) {
            $table->enum('status', array('open','ready','rendering','complete','returned','cancelled'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('renders', function (Blueprint $table) {
            $table->enum('status', array('open','ready','rendering','complete','returned'));
        });
    }
}
