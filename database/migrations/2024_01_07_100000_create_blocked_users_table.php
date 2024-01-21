<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBlockedUsersTable extends Migration
{
    /*
            CREATE TABLE `blocked_users` (
              `id` int(10) UNSIGNED NOT NULL,
              `userId` int(10) UNSIGNED NOT NULL,
              `teamId` int(10) UNSIGNED NOT NULL,
              `blockedUserId` int(10) UNSIGNED NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1;

            ALTER TABLE `blocked_users`
              ADD PRIMARY KEY (`id`);

            ALTER TABLE `blocked_users`
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
        Schema::create('blocked_users', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('userId')->unsigned()->index();
            $table->integer('teamId')->unsigned()->index();
            $table->integer('blockedUserId')->unsigned()->index();
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
        Schema::drop('blocked_users');
    }
}
