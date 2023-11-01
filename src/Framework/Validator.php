<?php

declare(strict_types=1);

namespace Framework;

use Framework\Contracts\RuleInterface;
use Framework\Exceptions\ValidationException;

// Not only our validator can use the default rules but users of 
// our framework can also add their own rules to the validator as long
// as the rule follows the RuleInterface contract

// Our Validator class is only responsible on how to store the rules and how
// to perform validations. It is not responsible on which field to validate 
// and what rule to use to validate that field from, this is the responsibility
// of the Application to provide for the framework which is in 
// the ValidatorService class.

// As a rule of thumb, the Framework should be considered tools, they should know
// the 'How' but not the 'What'. For example, our Router knows how to store routes
// and dispatch routes. However, it doesn't know what routes should be available in 
// the application. It's up to our Application to register routes (registerRoutes). 
// The same idea applies to our Container, our Container knows how to store
// dependencies and how to instantiate them. On the other hand, it doesn't
// have an immediate list of dependencies, our Application is responsible for
// registering the dependencies (container-definitions)
class Validator
{
	private array $rules = [];

	// Since the $rules array is private, the add() method is responsible
	// for adding custom rules to our validator
	public function add(string $alias, RuleInterface $rule)
	{
		$this->rules[$alias] = $rule;
	}

	public function validate(array $formData, array $fields)
	{
		$errors = [];

		foreach ($fields as $fieldName => $rules) {
			foreach ($rules as $rule) {
				$ruleParams = [];

				// A rule can have a rule parameter (e.g. 'age' => ['required', 'min:18'])
				// A rule parameter is not limited to one, they can be separated with comma
				if (str_contains($rule, ':')) {
					[$rule, $ruleParams] = explode(':', $rule);
					$ruleParams = explode(',', $ruleParams);
				}

				$ruleValidator = $this->rules[$rule];

				if ($ruleValidator->validate($formData, $fieldName, $ruleParams)) {
					continue;
				}

				$errors[$fieldName][] = $ruleValidator->getMessage(
					$formData,
					$fieldName,
					$ruleParams
				);
			}
		}

		if (count($errors)) {
			throw new ValidationException($errors);
		}
	}
}
