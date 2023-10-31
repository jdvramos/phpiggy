<?php

declare(strict_types=1);

namespace App\Services;

use Framework\Validator;
use Framework\Rules\{RequiredRule};

// It is a common practice to make Services injectable, add this to container-definitions.php
class ValidatorService
{
	private Validator $validator;

	public function __construct()
	{
		$this->validator = new Validator();
		// By default, we are not going to register rules with our framework. 
		// It's possible that developers may prefer to create their own
		// 'required' rule. This means every rule must be registered by the App
		$this->validator->add('required', new RequiredRule());
	}

	public function validateRegister(array $formData)
	{
		$this->validator->validate($formData);
	}
}
