<?php
require_once 'core/Router.php';
require_once 'models/users.php';

class Profile extends Handler
{
	public static function GET()
	{
		if (!isset($_SESSION['user'])) {
			header('Location: /login/');
			exit;
		}
		$user = $_SESSION['user'];
		require 'views/profile.php';
	}

	public static function PATCH()
	{
		$user = $_SESSION['user'] ?? null;
		if ($user === null) {
			http_error(403);
		}
		parse_str(file_get_contents('php://input'), $_PATCH);
		$subscriptions = $_PATCH['subscriptions'] ?? [];
		$subscriptions = array_filter(array_map('trim', $subscriptions));
		$user->subscriptions = $subscriptions;
		$user->save();
		readfile('components/subscriptions_saved.html');
	}
}

return Profile::class;
