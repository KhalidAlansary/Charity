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
		$router->register($route, $controller);
	}
}

// require all classes which may be stored in session
require_once 'models/users.php';
require_once 'models/payments.php';
session_start();

$router->dispatch();
