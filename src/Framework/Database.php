<?php

declare(strict_types=1);

namespace Framework;

// Globally available classes like PDO and PDOException must be manually
// imported when using a namespace
use PDO, PDOException;

// If this class gets intantiated, we're going to begin connecting to
// the database. Let's add the database connectivity logic to the __construct
// method
class Database
{
	public PDO $connection;

	public function __construct(
		string $driver,
		array $config,
		string $username,
		string $password
	) {
		/*
			The http_build_query() was introduced for building URLs. Luckily, it  
			can be used for building DSN strings. However, the default separator is
			'&', change this by using named arguments and passing the value ';' as
			the arg_separator
		*/
		$config = http_build_query(data: $config, arg_separator: ';');

		$dsn = "{$driver}:{$config}";

		/*
			The PDO class creates a connection to our database, it's important to store
			the instance. Otherwise, we will lose the connection. If we want to interact
			with our database we must do so through the variable. There are three arguments:
			$dsn, $username, and $password. The PDO class throws a PDOException if the 
			attempt to connect to the requested database fails. The problem with not catching
			this error is that it exposes the database url and credentials on the command line
			which can be a potential security issue. Here, we are catching the error in order
			to avoid this issue
		*/
		try {
			$this->connection = new PDO($dsn, $username, $password);
		} catch (PDOException $e) {
			die("Unable to connect to database");
		}
	}

	public function query(string $query)
	{
		$this->connection->query($query);
	}
}
