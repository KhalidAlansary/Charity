<?php
require_once 'models/users.php';
require_once 'core/Router.php';

return new class extends Handler
{
	public function GET()
	{
		require 'views/signup.php';
	}

	public function POST()
	{
		$user = User::signup(
			$_POST['name'],
			$_POST['email'],
			$_POST['password'],
			$_POST['type']
		);
		if ($user === false) {
			http_response_code(403);
			readfile('components/signup_error.html');
			exit;
		}

		$_SESSION['user'] = $user;
		http_response_code(303);
		header('HX-Redirect: /profile/');
	}
};
