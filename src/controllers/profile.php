<?php
require_once 'core/Router.php';
class Profile extends Handler
{
	public static function GET()
	{
		require_once 'models/users.php';
		if (!isset($_SESSION['user'])) {
			header('Location: /login/');
			exit;
		}
		$user = $_SESSION['user'];
		require 'views/profile.php';
		require_once 'models/users.php';
		if (!isset($_SESSION['user'])) {
			header('Location: /login/');
			exit;
		}
		$user = $_SESSION['user'];
		require 'views/profile.php';
	}
}

return Profile::class;
