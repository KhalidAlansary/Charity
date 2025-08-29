<?php
require_once 'core/ProtectedHandler.php';
require_once 'core/Router.php';
require_once 'models/users.php';

$handler = new class extends Handler
{
	public function GET()
	{
		if (!isset($_SESSION['user']) || !$_SESSION['user'] instanceof Admin) {
			header('Location: /login/');
			exit;
		}
		require 'views/admin/index.php';
	}
};

return new ProtectedHandler($handler, Admin::class);
