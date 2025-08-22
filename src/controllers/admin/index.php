<?php
require_once 'core/Router.php';


class AdminHome extends Handler
{
	public static function GET()
	{
		if (!isset($_SESSION['user']) || !$_SESSION['user'] instanceof Admin) {
			header('Location: /login/');
			exit;
		}
		require 'views/admin/index.php';
	}
}

return AdminHome::class;
