<?php

declare(strict_types=1);

namespace App\Controllers;

use Framework\TemplateEngine;
use App\Services\ValidatorService;

class AuthController
{
	public function __construct(
		private TemplateEngine $view,
		private ValidatorService $validatorService
	) {
	}

	public function registerView()
	{
		echo $this->view->render("register.php");
	}

	public function register()
	{
		// PHP automatically stores post data in a super global variable called $_POST
		// The $_POST only gets populated when using post request
		dd($_POST);
	}
}
