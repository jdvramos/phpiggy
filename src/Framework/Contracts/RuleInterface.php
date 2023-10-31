<?php

declare(strict_types=1);

namespace Framework\Contracts;

interface RuleInterface
{
	// If the validate() returns true, we consider the validation a success
	public function validate(array $data, string $field, array $params): bool;

	// If validation fails, an error message must be presented to the user
	public function getMessage(array $data, string $field, array $params): string;
}
