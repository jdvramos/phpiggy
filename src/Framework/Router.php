<?php

declare(strict_types=1);

namespace Framework;

class Router
{
	private array $routes = [];
	private array $middlewares = [];

	public function add(string $method, string $path, array $controller)
	{
		$path = $this->normalizePath($path);
		$this->routes[] =
			[
				'path' => $path,
				'method' => strtoupper($method),
				'controller' => $controller
			];
	}

	private function normalizePath(string $path): string
	{
		$path = trim($path, '/');
		$path = "/{$path}/";
		$path = preg_replace('#[/]{2,}#', '/', $path);
		return $path;
	}

	public function dispatch(string $path, string $method, Container $container = null)
	{
		$path = $this->normalizePath($path);
		$method = strtoupper($method);

		foreach ($this->routes as $route) {
			if (!preg_match("#^{$route['path']}$#", $path) || $route['method'] !== $method) {
				continue;
			}

			[$class, $function] = $route['controller'];

			// It is completely acceptable to provide a string after 
			// the 'new' keyword as long as the string points to a 
			// specific class with the namespace
			$controllerInstance = $container ? $container->resolve($class) : new $class;

			// It's also acceptable to put a string after the arrow operator. 
			// PHP tries to resolve the value in the string to a method in the class. 
			// If it finds the method, it would allow us to invoke the method like any other method
			$action = fn () => $controllerInstance->$function();

			foreach ($this->middlewares as $middleware) {
				$middlewareInstance = $container ? $container->resolve($middleware) : new $middleware;
				$action = fn () => $middlewareInstance->process($action);
			}

			$action();

			return;
		}
	}

	public function addMiddleware(string $middleware)
	{
		$this->middlewares[] = $middleware;
	}
}
