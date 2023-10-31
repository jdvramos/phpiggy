<?php

declare(strict_types=1);

namespace Framework\Exceptions;

use RuntimeException;

// We extending the RuntimeException class over the Exception class because 
// the RuntimeException category is meant for errors that will only occur
// while the application is running (at runtime). It's meant for code that
// does not have to be fixed, but handled
class ValidationException extends RuntimeException
{
	public function __construct(int $code = 422)
	{
		parent::__construct(code: $code);
	}
}
