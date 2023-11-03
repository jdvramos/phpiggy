<?php

/*
	composer run-script phpiggy
*/

include __DIR__ . '/src/Framework/Database.php';

use Framework\Database;

$db = new Database('mysql', [
	'host' => 'localhost',
	'port' => 3306,
	'dbname' => 'phpiggy',
], 'root', '');

$query = "SELECT * FROM products";

/*
	The variable is called stmt (statement). It's a common practice to
	refer to the result of a query as statement. After all, the query()
	function returns a type called PDOStatement
*/
$stmt = $db->connection->query($query, PDO::FETCH_ASSOC);

var_dump($stmt->fetchAll(PDO::FETCH_OBJ));
