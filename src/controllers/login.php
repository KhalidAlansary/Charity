<?php
require_once 'core/Router.php';
class Login extends Handler
{
	public static function GET()
	{
		require 'views/login.php';
	}

	public static function POST()
	{
		require_once 'models/users.php';
		$email = $_POST['email'];
		$password = $_POST['password'];

		$user = User::login($email, $password);

		if ($user === false) {
			http_response_code(403);
			readfile('components/login_error.html');
		} else {
			$_SESSION['user'] = $user;
			http_response_code(303);
			header('HX-Redirect: /profile/');
		}
	}
}

return Login::class;
