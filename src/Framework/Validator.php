<?php

declare(strict_types=1);

namespace Framework;

use Framework\Contracts\RuleInterface;

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
		foreach ($fields as $fieldName => $rules) {
			foreach ($rules as $rule) {
				$ruleValidator = $this->rules[$rule];

				if ($ruleValidator->validate($formData, $fieldName, [])) {
					continue;
				}

				echo "Error";
			}
		}
	}
}
