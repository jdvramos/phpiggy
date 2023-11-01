<?php

declare(strict_types=1);

namespace App\Config;

use Framework\App;
use App\Middleware\{TemplateDataMiddleware, ValidationExceptionMiddleware, SessionMiddleware};

// The order of our middlewares does matter. Our ValidationExceptionMiddleware class 
// doesn't have access to sessions until the session has been enabled in the 
// SessionMiddleware. Therefore, the SessionMiddleware must be registered last. Middleware
// registered last gets executed first. Therefore, it's guaranteed for sessions to be enabled
// before anything else
function registerMiddleware(App $app)
{
	$app->addMiddleware(TemplateDataMiddleware::class);
	$app->addMiddleware(ValidationExceptionMiddleware::class);
	$app->addMiddleware(SessionMiddleware::class);
}
