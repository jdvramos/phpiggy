<?php

declare(strict_types=1);

namespace App\Middleware;

use Framework\Contracts\MiddlewareInterface;
use Framework\TemplateEngine;

// You may be wondering, why is it called FlashMiddleware? Flashing is a concept in
// programming where data is deleted after a single request. Errors should only appear
// once. After they appear on the page, there isn't a reason to keep track of them
class FlashMiddleware implements MiddlewareInterface
{
	// Middlewares can also have dependencies, in this middleware we are going to need
	// an instance of the TemplateEngine. Luckily, we already set this up in the 
	// Router.php so we are sure that middlewares will be injected with its required
	// dependencies
	public function __construct(private TemplateEngine $view)
	{
	}

	public function process(callable $next)
	{
		// With the TemplateEngine injected in the FlashMiddleware, we can call the addGlobal()
		// method, pass in 'errors' as the key and errors stored in session when its truthy.
		// With this, we exposed our errors to any template that needs them
		$this->view->addGlobal('errors', $_SESSION['errors'] ?? []);

		unset($_SESSION['errors']);

		$this->view->addGlobal('oldFormData', $_SESSION['oldFormData'] ?? []);

		unset($_SESSION['oldFormData']);

		$next();
	}
}
