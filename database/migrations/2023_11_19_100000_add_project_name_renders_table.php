<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class AddProjectNameRendersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // NB Not sure if this is correct way of adding an enum value
        // ALTER TABLE `renders` ADD COLUMN `c4dProjectName` VARCHAR(255) NOT NULL AFTER `c4dProjectWithAssets`;
        Schema::table('renders', function ($table) {
            $table->string('c4dProjectName')->after('c4dProjectWithAssets');
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
            $table->string('c4dProjectName');
        });
    }
}
