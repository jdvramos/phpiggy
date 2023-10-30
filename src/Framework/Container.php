<?php

declare(strict_types=1);

namespace Framework;

use ReflectionClass, ReflectionNamedType;
use Framework\Exceptions\ContainerException;

class Container
{
	private array $definitions = [];
	private array $resolved = [];

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

			// At this point, the parameter is already validated. We are going to invoke the
			// get() method and push its return value into the $dependencies array
			$dependencies[] = $this->get($type->getName());
		}

		// The Reflection API is not limited to inspecting the structure of a class. We can
		// also use it to create new instances. The newInstanceArgs() method is available
		// for instantiating the class being reflected. It accepts an array of arguments
		// that can be passed to the __construct() method of the class. The return value
		// is a new instance
		return $reflectionClass->newInstanceArgs($dependencies);
	}

	// The $id parameter should point to a specific item in our array 
	// from container-definitions.php by its key.
	public function get(string $id)
	{
		// Validate the $id, it's possible that there isn't a dependency with given $id
		if (!array_key_exists($id, $this->definitions)) {
			throw new ContainerException("Class {$id} does not exist in container.");
		}

		// To prevent a dependency from being instantiated more than once (Singleton Pattern)
		if (array_key_exists($id, $this->resolved)) {
			return $this->resolved[$id];
		}

		// As a reminder, the items in the $definitions array are factory functions
		$factory = $this->definitions[$id];
		// If we want the dependency we must invoke the function
		$dependency = $factory();

		// To prevent a dependency from being instantiated more than once (Singleton Pattern)
		// Adding it to the resolved array so that the $dependency can be checked later
		$this->resolved[$id] = $dependency;

		return $dependency;
	}
}
