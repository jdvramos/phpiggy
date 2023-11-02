<?php

/*
	composer run-script phpiggy
*/

/*
	The mysql driver is compatible with MariaDB
*/
$driver = 'mysql';

/*
	The http_build_query() was introduced for building URLs. Luckily, it  
	can be used for building DSN strings. However, the default separator is
	'&', change this by using named arguments and passing the value ';' as
	the arg_separator
*/
$config = http_build_query(data: [
	'host' => 'localhost',
	'port' => 3306,
	'dbname' => 'phpiggy',
], arg_separator: ';');

$dsn = "{$driver}:{$config}";
$username = 'root';
$password = '';

/*
	The PDO class creates a connection to our database, it's important to store
	the instance. Otherwise, we will lose the connection. If we want to interact
	with our database we must do so through the variable. There are three arguments:
	$dsn, $username, and $password.
*/
$db = new PDO($dsn, $username, $password);

echo "Connected to database";
