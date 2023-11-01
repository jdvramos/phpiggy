<?php

declare(strict_types=1);

namespace App\Middleware;

use Framework\Contracts\MiddlewareInterface;

// By default, PHP disables session. In order to use it we must run the session_start() first
class SessionMiddleware implements MiddlewareInterface
{
	public function process(callable $next)
	{
		session_start();
		$next();
	}
}
