

Goto:  /Users/brianetheridge/Homestead

Run: vagrant up

Run: vagrant ssh # to access the running environment and MySQL

cd Code/srsapi


# db
    create database srs;

    GRANT ALL ON srs.* TO 'brian'@'localhost' IDENTIFIED BY 'Canopy9098';
    GRANT ALL ON srs.* TO 'srs_admin'@'localhost' IDENTIFIED BY 'Candoobly9';

    php artisan migrate

