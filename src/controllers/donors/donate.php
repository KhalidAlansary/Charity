<?php
require_once 'core/ProtectedHandler.php';
require_once 'core/Router.php';
require_once 'models/payments.php';
require_once 'models/users.php';

$handler = new class extends Handler
{
	public function GET()
	{
		require 'views/donors/donate.php';
	}

	public function POST()
	{
		$donor = $_SESSION['user'];
		$_SESSION['donation'] = new Donation($_POST['amount'], $donor);
		readfile('components/donate/confirm.html');
	}

	public function PATCH()
	{
		$donation = $_SESSION['donation'] ?? null;
		if (!($donation instanceof Donation))
			http_error(400);

		$donation->proceed();
		unset($_SESSION['donation']);
		header('HX-Redirect: /donors/');
	}

	public function DELETE()
	{
		$donation = $_SESSION['donation'] ?? null;
		if (!($donation instanceof Donation))
			http_error(400);

		$donation->cancel();
		unset($_SESSION['donation']);
		header('HX-Redirect: /donors/');
		exit;
	}
};

return new ProtectedHandler($handler, Donor::class);
