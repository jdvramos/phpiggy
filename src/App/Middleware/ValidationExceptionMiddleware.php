<?php

declare(strict_types=1);

namespace App\Middleware;

use Framework\Contracts\MiddlewareInterface;
use Framework\Exceptions\ValidationException;

class ValidationExceptionMiddleware implements MiddlewareInterface
{
	// We wrapped the $next() function to a try block since the AuthController, for example,
	// uses the ValidatorService, and the ValidatorService uses the Validator class. In the
	// Validator's validate() function there's a possibility that a ValidationException error 
	// could be thrown when one or more fields failed to pass the validation. In the case that
	// there's a ValidationException error thrown, the catch block here will catch the error
	// and perform a redirect to the /register page

	// Keep in mind that this middleware still runs BEFORE and not after the controller.
	// Adding a try/catch is a trick so that we don't have to create a middleware that 
	// will only run after the controller
	public function process(callable $next)
	{
		try {
			$next();
		} catch (ValidationException $e) {
			// Sessions are useful for storing errors. Sessions are a feature for storing data
			// longer than a single request. Since redirection is another type of request, our
			// errors object here will be destroyed by PHP, we use sessions to persist the 
			// errors object in order to be used and displayed by redirected page
			$_SESSION['errors'] = $e->errors;

			// The HTTP_REFERRER item is a special value available after form submission.
			// It stores the url where the form was submitted. Therefore, we'll always be
			// redirected to the same url with the original form 
			$referer = $_SERVER['HTTP_REFERER'];
			redirectTo($referer);
		}
	}
}
