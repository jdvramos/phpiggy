<?php

declare(strict_types=1);

namespace App\Services;

use Framework\Validator;

// It is a common practice to make Services injectable, add this to container-definitions.php
class ValidatorService
{
	private Validator $validator;

	public function __construct()
	{
		$this->validator = new Validator();
	}

	public function validateRegister(array $formData)
	{
		$this->validator->validate($formData);
	}
}
