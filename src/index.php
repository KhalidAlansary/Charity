<?php
require_once 'core/Router.php';

$router = Router::getInstance();

$controllers_iterator = new RecursiveIteratorIterator(
	new RecursiveDirectoryIterator('controllers')
);

foreach ($controllers_iterator as $controller => $fileinfo) {
	if ($fileinfo->getExtension() === 'php') {
		$route = preg_replace('/\.php$/', '', $controller);
		$route = preg_replace('/index$/', '', $route);
		$route = preg_replace('/^controllers/', '', $route);
		// PERF: would lazy loading be better?
		$router->register($route, require_once $controller);
	}
}

$router->dispatch();
