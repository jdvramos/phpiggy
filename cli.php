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

echo $dsn;
