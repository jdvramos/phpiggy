<?php

declare(strict_types=1);

namespace Framework;

class TemplateEngine
{
	private array $globalTemplateData = [];

	public function __construct(private string $basePath)
	{
	}

	public function render(string $template, array $data = [])
	{
		extract($data, EXTR_SKIP);
		extract($this->globalTemplateData, EXTR_SKIP);

		// This will tell PHP to store content in an output buffer
		// We are also preventing PHP from sending content to the browser immediately
		// until every line is finished running or the buffer is closed 
		ob_start();

		include $this->resolve($template);

		// Before closing the buffer, we must retrieve the content otherwise, PHP may 
		// output the content early. To do this, call the ob_get_contents(), 
		// this function searches for an active output buffer, if it finds one
		// the content from the buffer will be returned from the function as a
		// string. Now we can return this $output from our function
		$output = ob_get_contents();

		// After ob_get_contents() you must always run ob_end_clean(). This functions
		// performs two actions. First, it will stop output buffer from running.
		// PHP will revert to its original behaviour. Secondly, the output buffer will
		// have its memory wiped. Contents stored in the output buffer will be lost
		// We should always end the buffer, otherwise our server will be wasting resources
		ob_end_clean();

		return $output;
	}

	public function resolve(string $path)
	{
		return "{$this->basePath}/{$path}";
	}

	public function addGlobal(string $key, mixed $value)
	{
		$this->globalTemplateData[$key] = $value;
	}
}
