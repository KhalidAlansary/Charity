<?php
require_once 'core/Router.php';

return new class extends Handler
{
	public function GET()
	{
		require 'views/login.php';
	}

	public function POST()
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
};
