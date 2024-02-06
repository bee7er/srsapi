

Goto:  /Users/brianetheridge/Homestead

Run: vagrant up

Run: vagrant ssh # to access the running environment and MySQL

cd Code/srsapi


# db
    create database srs;

    GRANT ALL ON srs.* TO 'brian'@'localhost' IDENTIFIED BY 'Canopy9098';
    GRANT ALL ON srs.* TO 'srs_admin'@'localhost' IDENTIFIED BY 'Candoobly9';

    php artisan migrate

    ERROR 1067 (42000): Invalid default value for 'created_at'

        show variables like 'sql_mode' ;

        # Put the following lines at the start:

            SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
            START TRANSACTION;
            SET time_zone = "+00:00";

            ALTER TABLE `users` CHANGE `user_token` `user_token` VARCHAR(16) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL;

# bash scripting
    ######## To invoke python !/usr/bin/env python3
    # Example
    # nohup python /path/to/test.py &
