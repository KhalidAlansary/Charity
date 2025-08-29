<?php
abstract class RouteDecorator extends Handler
{
	protected $wrappee;

	public function __construct(Handler $wrappee)
	{
		$this->wrappee = $wrappee;
	}
}

class ProtectedHandler extends RouteDecorator
{
	private $requiredRole;

	public function __construct(Handler $wrappee, string $requiredRole)
	{
		parent::__construct($wrappee);
		$this->requiredRole = $requiredRole;
	}

	public function __call($method, $args)
	{
		if (!isset($_SESSION['user'])) {
			http_error(401);
		}
		if (!$_SESSION['user'] instanceof $this->requiredRole) {
			http_error(403);
		}
		return call_user_func_array([$this->wrappee, $method], $args);
	}
}
