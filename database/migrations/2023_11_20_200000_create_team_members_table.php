<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeamMembersTable extends Migration
{
    /**
    CREATE TABLE `team_members` (
    `id` int(10) UNSIGNED NOT NULL,
    `teamId` int(10) UNSIGNED NOT NULL,
    `userId` int(10) UNSIGNED NOT NULL,
    `status` enum('active','inactive') COLLATE utf8_unicode_ci NOT NULL,
    `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
    `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

    ALTER TABLE `team_members`
    ADD PRIMARY KEY (`id`),
    ADD KEY `team_members_teamId` (`teamId`),
    ADD KEY `team_members_userId` (`userId`);

    ALTER TABLE `team_members`
    MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
     */
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Render detail records
        Schema::create('team_members', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('teamId')->unsigned()->index();
            $table->integer('userId')->unsigned()->index();
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
        Schema::drop('team_members');
    }
}
