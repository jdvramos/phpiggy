<?php

declare(strict_types=1);

namespace App\Services;

use Framework\Validator;
use Framework\Rules\{RequiredRule, EmailRule, MinRule};

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
		$this->validator->add('email', new EmailRule());
		$this->validator->add('min', new MinRule());
	}

	public function validateRegister(array $formData)
	{
		$this->validator->validate($formData, [
			'email' => ['required', 'email'],
			'age' => ['required', 'min:18'],
			'country' => ['required'],
			'socialMediaURL' => ['required'],
			'password' => ['required'],
			'confirmPassword' => ['required'],
			'tos' => ['required'],
		]);
	}
}
