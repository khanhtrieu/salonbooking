[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%207.2-8892BF.svg?style=flat-square)](https://php.net/)

<p align="center">
<h1>AO (All in One)</h1>
</p>

## About AO Project
--------
AO team from CSUF, we are create aim to practice Agile Process.

## About AO Project
--------

AO wishes to use an online website to give salon services at home. The website helps customers schedule appointments for any required services like Haircut, Beard trims, nail filing, facial, pedicure, manicure, spa, hair color, hair straightening, hair wash and styling, tan removing, waxing, facial clean up, makeup, and more

## Server configuration
--------

To install the latest AO, you need a web server running PHP 7.2+ and any flavor of MySQL 5.0+ (MySQL, MariaDB, Percona Server, etc.). Versions between 1.7.0 and 1.7.6 work with PHP 5.6+.

You will also need a database administration tool, such as phpMyAdmin, in order to create a database for AO.
We recommend the Apache or Nginx web servers (check out our [example Nginx configuration file][example-nginx]).


Installation
--------
1. Upload the source code to server
2. Change the configuration of the database connection in .env
3. Run command line to create database and table
```
php bin/console doctrine:database:create` to create the database
php bin/console doctrine:schema:update --force` to generate the tables
```
