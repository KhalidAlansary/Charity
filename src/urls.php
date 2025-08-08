<?php
$routes = [
	'/' => 'index.php',
	'/volunteers/' => 'volunteers.php',
	'/donors/' => 'donors.php',
	'/login/' => 'login.php',
	'/signup/' => 'signup.php',
	'/profile/' => 'profile.php',
];

$path = rtrim($_SERVER['REQUEST_URI'], "/") . '/';
if (array_key_exists($path, $routes)) {
	require 'controllers/' . $routes[$path];
} else {
	http_response_code(404);
	echo "404 Not Found";
}
