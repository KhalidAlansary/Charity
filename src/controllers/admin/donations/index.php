<?php
require_once 'core/Router.php';
require_once 'models/payments.php';
require_once 'models/users.php';

class AdminHome extends Handler
{
	public static function GET()
	{
		if (!isset($_SESSION['user']) || !$_SESSION['user'] instanceof Admin) {
			header('Location: /login/');
			exit;
		}
		$donations = Donation::getAllPending();
		require 'views/admin/donations/index.php';
	}
}

return AdminHome::class;
