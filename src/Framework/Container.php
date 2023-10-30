<?php

declare(strict_types=1);

namespace Framework;

use ReflectionClass, ReflectionNamedType;
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

		$params = $constructor->getParameters();

		// Since not all class' constructor function accepts a parameter, add a check
		if (count($params) === 0) {
			return new $className;
		}

		// The dependencies variable is going to store the instances or dependencies required
		// by our controller. At the moment, all we have is the array of parameters, the goal
		// is to loop through each parameter, as we do so we'll create an instance for the 
		// respective parameter
		$dependencies = [];

		// Since $params returns an array of ReflectionParameter type (ReflectionParameter[])
		// it's safe to assume that $param is type of ReflectionParameter. The ReflectionParameter
		// class has methods for viewing information on a specific parameter
		foreach ($params as $param) {
			// Get the name of the parameter
			$name = $param->getName();
			// Get the data type of the parameter
			$type = $param->getType();
			// We are only accepting parameters that are type hinted
			if (!$type) {
				throw new ContainerException("Failed to resolve class {$className} because param {$name} is missing a type hint.");
			}
			// We are only accepting parameters that have a type of ReflectionNamedType and types
			// that are not built in to PHP. A parameter has the ReflectionNamedType when there 
			// is only one type hint
			if (!$type instanceof ReflectionNamedType || $type->isBuiltin()) {
				throw new ContainerException("Failed to resolve class {$className} because invalid param name.");
			}
		}

		dd($params);
	}
}
