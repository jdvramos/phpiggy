<?php

declare(strict_types=1);

namespace Framework;

use ReflectionClass;
use Framework\Exceptions\ContainerException;

class Container
{
	private array $definitions = [];

	public function addDefinitions(array $newDefinitions)
	{
		// The spread operator is slightly faster than the array_merge() function
		$this->definitions = [...$this->definitions, ...$newDefinitions];
	}

	public function resolve(string $className)
	{
		$reflectionClass = new ReflectionClass($className);

		// As we know, abstract classes cannot be instantiated
		if (!$reflectionClass->isInstantiable()) {
			throw new ContainerException("Class {$className} is not instantiable");
		}

		// This retrieves the class' constructor function
		$constructor = $reflectionClass->getConstructor();

		// Since not all class have constructor function, add a check
		if (!$constructor) {
			return new $className;
		}

		dd($constructor);
	}
}
