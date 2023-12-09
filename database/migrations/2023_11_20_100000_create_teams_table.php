<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeamsTable extends Migration
{
    /**
    CREATE TABLE `teams` (
    `id` int(10) UNSIGNED NOT NULL,
    `adminUserId` int(10) UNSIGNED NOT NULL,
    `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
    `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
    `status` enum('active','inactive') COLLATE utf8_unicode_ci NOT NULL,
    `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
    `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

    ALTER TABLE `teams`
    ADD PRIMARY KEY (`id`),
    ADD KEY `teams_adminUserId` (`adminUserId`);

    ALTER TABLE `teams`
    MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
     */

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Render header records
        Schema::create('teams', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('adminUserId')->unsigned()->index();
            $table->string('name');
            $table->string('description');
            $table->enum('status', array('active','inactive'));
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
        Schema::drop('teams');
    }
}
