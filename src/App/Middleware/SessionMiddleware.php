<?php

declare(strict_types=1);

namespace App\Middleware;

use Framework\Contracts\MiddlewareInterface;
use App\Exceptions\SessionException;

// By default, PHP disables session. In order to use it we must run the session_start() first
class SessionMiddleware implements MiddlewareInterface
{
	public function process(callable $next)
	{
		// Session might be activated by a package you installed by Composer that uses sessions
		// Multiple sessions are possible and that's not what we want
		if (session_status() === PHP_SESSION_ACTIVE) {
			throw new SessionException("Session already active.");
		}

		// If this returns true, the data has already been sent to the browser. Therefore, we
		// can't active a session
		if (headers_sent($filename, $line)) {
			throw new SessionException("Headers already sent. Consider enabling output buffering. Data outputted from {$filename} - Line: {$line}");
		}

		session_start();
		$next();
	}
}
