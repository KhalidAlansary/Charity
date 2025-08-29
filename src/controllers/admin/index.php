<?php
require_once 'core/Router.php';

return new class extends Handler
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
