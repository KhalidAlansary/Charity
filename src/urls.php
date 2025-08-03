<?php
$routes = [
	'/' => 'index.php',
	'/volunteer/' => 'volunteer/index.php',
	'/volunteer/login/' => 'volunteer/login.php',
	'/volunteer/signup/' => 'volunteer/signup.php',
	'/volunteer/profile/' => 'volunteer/profile.php',
];

$path = rtrim($_SERVER['REQUEST_URI'], "/") . '/';
if (array_key_exists($path, $routes)) {
	require_once 'controllers/' . $routes[$path];
} else {
	http_response_code(404);
	echo "404 Not Found";
}
