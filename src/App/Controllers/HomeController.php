<?php

declare(strict_types=1);

namespace App\Controllers;

use Framework\TemplateEngine;
use App\Config\Paths;

class HomeController
{

	public function __construct(private TemplateEngine $view)
	{
	}

	// The controllers no longer are required to provide a title. It's completely optional
	// Check the AboutController where it passes an array with title as an element. If you
	// ever removed it our app will use the global title variable 
	public function home()
	{
		echo $this->view->render("index.php");
	}
}
