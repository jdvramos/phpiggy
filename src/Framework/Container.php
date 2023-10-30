<?php

declare(strict_types=1);

namespace Framework;

class Container
{
	private array $definitions = [];

	public function addDefinitions(array $newDefinitions)
	{
		// The spread operator is slightly faster than the array_merge() function
		$this->definitions = [...$this->definitions, ...$newDefinitions];
		dd($this->definitions);
	}
}
