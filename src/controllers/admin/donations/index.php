<?php
require_once 'core/Router.php';
require_once 'models/payments.php';

class AdminHome extends Handler
{
	public static function GET()
	{
		// TODO: check if user is authenticated as admin
		require_once 'models/users.php';
		$donations = Donation::getAllPending();
		require 'views/admin/donations/index.php';
	}
}

return AdminHome::class;
