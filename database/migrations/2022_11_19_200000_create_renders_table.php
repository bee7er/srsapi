<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRendersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Render header records
        Schema::create('renders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('submitted_by_user_id')->unsigned()->index();
            $table->enum('status', array('open','ready','rendering','complete'));
            $table->string('c4dProjectWithAssets');
            $table->string('outputFormat');
            $table->integer('from');
            $table->integer('to');
            $table->string('overrideSettings');
            $table->string('customFrameRanges');
            $table->dateTime('completed_at')->nullable();
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
        Schema::drop('renders');
    }
}
