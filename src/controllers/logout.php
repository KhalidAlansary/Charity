<?php
require_once 'core/Router.php';
class Logout extends Handler
{
	public static function GET()
	{
		$_SESSION = [];

		if (ini_get('session.use_cookies')) {
			$params = session_get_cookie_params();
			setcookie(
				session_name(),
				'',
				time() - 42000,
				$params['path'],
				$params['domain'],
				$params['secure'],
				$params['httponly']
			);
		}

		session_destroy();
		header('Location: /');
	}
}

return Logout::class;
