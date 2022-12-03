<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RendersAddRenderedAt extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('renders', function (Blueprint $table) {
            // Add the new column
            $table->dateTime('rendered_at')->nullable();
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
            // Drop the new column
            $table->dropColumn('rendered_at');
        });
    }
}
