<?php

declare(strict_types=1);

namespace Framework\Rules;

use Framework\Contracts\RuleInterface;
use InvalidArgumentException;

class MinRule implements RuleInterface
{
	public function validate(array $data, string $field, array $params): bool
	{
		// It's possible that a developer using this rule forgot to supply the min parameter
		if (empty($params[0])) {
			throw new InvalidArgumentException("Minimum length not specified");
		}

		$length = (int) $params[0];

		return $data[$field] >= $length;
	}

	public function getMessage(array $data, string $field, array $params): string
	{
		return "Must be at least {$params[0]}";
	}
}
