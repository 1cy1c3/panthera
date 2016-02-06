Panthera
============

# Description
A content management system which manages a website.

## Prerequisites
+ HTTP server like Apache or Nginx
+ PHP support
+ Composer which manages packages regarding PHP
+ MySQL server

## Installation
At first, clone or download this project. Afterwards, navigate in the folder `lib` and execute the following commands:
```
$ cd lib
$ curl -sS https://getcomposer.org/installer | sudo php -- --install-dir=/usr/local/bin --filename=composer
$ composer install
```
The composer installs required packages based on the `composer.json` in the folder `vendor`. The next step is to import the SQL file. For this reason, create a database, use it and import the SQL file, for example:
```
$ mysql -h localhost -u myname -pmypass
mysql> CREATE DATABASE dbname;
mysql> USE dbname;
mysql> SET autocommit=0; source panthera.sql; COMMIT;
mysql> EXIT
```
After the import, you're able to delete the sql directory. Afterwards, you have to clone the submodule `jQuery` and build the framework:
```
$ cd panthera
$ git submodule update --init
$ cd web/assets/external/jquery
$ sudo npm run build
```
For this process, you need the program `npm`. The next step is different. On the one hand, you're able to deploy the panthera directory to the root folder of the webserver. On the other hand, you're able to create a virtual host and deploy the folder in the defined directory. Afterwards, call the frontend with the url `yourhost/web/index.php` or the backend with `yourhost/web/index.php/backend/`.
At least, create a file called `prod.php` in the folder `res/conf` with the following content:
```
<?php
$app ['debug'] = false;

error_reporting ( E_ERROR | E_WARNING | E_PARSE );

$app ['dbs.options'] = array (
		'mysql_read' => array (
				'driver' => 'pdo_mysql',
				'host' => 'localhost',
				'dbname' => 'panthera',
				'user' => 'panthera',
				'password' => 'panthera',
				'charset' => 'utf8' 
		),
		'mysql_write' => array (
				'driver' => 'pdo_mysql',
				'host' => 'localhost',
				'dbname' => 'panthera',
				'user' => 'panthera',
				'password' => 'panthera',
				'charset' => 'utf8' 
		) 
);

define ( 'DB_PRAEFIX', 'cms_' );

define ( 'LOG_LEVEL', Monolog\Logger::ERROR );
```
It's very important to adapt the keys `host`, `dbname`, `user` and `password` with the correct values to communicate with the database.

## Usage
At first, explore the views such as `Settings` and `Pages` regarding the backend. To do this, log in with the name `panthera` and password `panthera`. At the moment, it's possible, inter alia to do the following operations:
+ Set specific settings like the path to the frontend layout
+ Create, edit and delete own plugins in the form of widgets regarding the dashboard
+ Create, edit and delete menus and pages
+ View the database structure
+ Deal with files and directories
+ Permanent extensive logs
+ Set placeholder regarding the frontend to link it with the backend

In order to simplify the facts, data are created in terms of backend and frontend. For example, you're able to see the menu, pages and a breadcrumb in the frontend layout.

## Note
So you do not always enter `index.php`, there are a file `web/.htaccess` which makes possible to control the server behaviour. For example, in relation to your webserver such as `Apache`, write the following code:
```
<IfModule mod_rewrite.c>
    Options -MultiViews
    RewriteEngine On
    RewriteBase /web
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [QSA,L]
</IfModule>
```
Thereafter, it's possible to call the frontend and backend without the string `index.php`.

## More information
Generate the documentation regarding the special comments with a command in your terminal or with an IDE such as Eclipse. The responsible program called `phpDocumentor`.
Afterwards, you will get a website with helpful information about the code. Furthermore, read the documentation about the framework `Silex` at http://silex.sensiolabs.org/documentation.
