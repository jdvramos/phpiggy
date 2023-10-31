<?php

declare(strict_types=1);

namespace Framework;

use Framework\Contracts\RuleInterface;

// Not only our validator can use the default rules but users of 
// our framework can also add their own rules to the validator as long
// as the rule follows the RuleInterface contract
class Validator
{
	private array $rules = [];

	// Since the $rules array is private, the add() method is responsible
	// for adding custom rules to our validator
	public function add(string $alias, RuleInterface $rule)
	{
		$this->rules[$alias] = $rule;
	}

	public function validate(array $formData)
	{
		dd($formData);
	}
}
