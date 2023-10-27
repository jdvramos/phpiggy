<?php

declare(strict_types=1);

// It's named dd as short for dump and die
function dd(mixed $value)
{
	echo "<pre>";
	var_dump($value);
	echo "</pre>";
	// When we call the dd() we don't want any further code below it to be rendered
	die();
}

// It's named e as short for escape
function e(mixed $value): string
{
	return htmlspecialchars((string) $value);
}
