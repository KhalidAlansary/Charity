<?php
require_once 'core/ProtectedHandler.php';
require_once 'core/Router.php';
require_once 'models/users.php';

$handler = new class extends Handler
{
	public function GET()
	{
		$user = $_SESSION['user'];
		require 'views/profile.php';
	}

	public function PATCH()
	{
		$user = $_SESSION['user'];
		parse_str(file_get_contents('php://input'), $_PATCH);
		$subscriptions = $_PATCH['subscriptions'] ?? [];
		$subscriptions = array_filter(array_map('trim', $subscriptions));
		$user->subscriptions = $subscriptions;
		$user->save();
		readfile('components/subscriptions_saved.html');
	}
};

return new ProtectedHandler($handler, User::class);
