<?php
require_once 'core/ProtectedHandler.php';
require_once 'core/Router.php';
require_once 'models/payments.php';
require_once 'models/users.php';

$handler = new class extends Handler
{
	public function GET()
	{
		$donations = Donation::getAllPending();
		require 'views/admin/donations/index.php';
	}
};

return new ProtectedHandler($handler, Admin::class);
