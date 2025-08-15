<?php
require_once 'core/singletons.php';

class Router extends Singleton
{
	private $routes = [];

	public function register(string $route, $handler): void
	{
		$route = rtrim($route, '/') . '/';
		$this->routes[$route] = $handler;
	}

	// PERF: Routes parsing and registering is repeated for each request.
	// Maybe cache it?
	public function dispatch()
	{
		$path = rtrim($_SERVER['REQUEST_URI'], '/') . '/';

		foreach ($this->routes as $route => $handler) {
			// Transform the route to a regex pattern
			$pattern = '#^' . preg_replace('/\[(\w+)]/', '([\w-]+)', $route) . '$#';

			if (preg_match($pattern, $path, $paramsValues)) {
				array_shift($paramsValues);

				$params = [];
				if (preg_match_all('/\[(\w+)]/', $route, $paramsNames)) {
					$params = array_combine($paramsNames[1], $paramsValues);
				}
				$method = $_SERVER['REQUEST_METHOD'];
				call_user_func_array([$handler, $method], $params);
				return;
			}
		}
		http_error(404);
	}
}

abstract class Handler
{
	public static function __callStatic(string $method, array $args)
	{
		http_error(405);
	}
}

function http_error(int $code): void
{
	http_response_code($code);
	readfile("pages/errors/$code.html");
	exit;
}
