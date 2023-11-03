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

$search = "Hats";

$query = "SELECT * FROM products WHERE name=:name";

/*
	The variable is called stmt (statement). It's a common practice to
	refer to the result of a query as statement. After all, the query()
	function returns a type called PDOStatement. However, the query()
	method executes the query immediately, leaving us vulnerable to SQL 
	injection attacks. As an alternative, use the prepare() method instead.
	Unlike, query() the prepare() method does not execute the query immediately.
	We must manually execute the query ourselves through the $stmt variable by
	calling the execute() method. The prepare() method allows us to use
	prepared statements which is the best solution to prevent SQL injection attacks
*/
$stmt = $db->connection->prepare($query);

/*
	We can also bind the values into the query without immediately executing it
*/
$stmt->bindValue('name', $search, PDO::PARAM_STR);

/*
	Here, we are using the execute() method to manually execute the query ourselves.
	The execute() method does accept an optional argument array called $params. You
	must supply your query with placeholders (?) and pass in the array $params if you
	want to prevent SQL injection attacks. The order of array represents the order of
	placeholders. The first placeholder (?) will be replaced by the first item in 
	the array. To demonstrate how prepared statements prevent such attacks, we set the
	$search variable as before:
		$search = "Hats' OR 1=1 -- ";
	However, instead of returning all the rows of the table the result only returns an
	empty array. This means that SQL validates the value before the value gets inserted
	into the query.

	Instead of using the positional placeholder (?) you can instead use named
	placeholders (:name_of_param). If you use named placeholders, you then must use
	an associative array in as $params in the execute() method:
		$stmt->execute([
			'name' => $search
		]);
*/

$stmt->execute();

var_dump($stmt->fetchAll(PDO::FETCH_OBJ));
