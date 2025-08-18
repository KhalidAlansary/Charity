<?php
require_once 'core/Router.php';
require_once 'models/payments.php';
require_once 'models/users.php';

class Donate extends Handler
{
	public static function GET()
	{
		require 'views/donors/donate.php';
	}

	public static function POST()
	{
		$donor = $_SESSION['user'] ?? null;
		if (!($donor instanceof Donor)) {
			header('HX-Redirect: /login/');
			exit;
		}
		$_SESSION['donation'] = new Donation($_POST['amount'], $donor);
		readfile('components/donate/confirm.html');
	}

	public static function PATCH()
	{
		$donation = $_SESSION['donation'] ?? null;
		if (!($donation instanceof Donation))
			http_error(400);

		$donation->proceed();
		unset($_SESSION['donation']);
		header('HX-Redirect: /donors/');
	}

	public static function DELETE()
	{
		$donation = $_SESSION['donation'] ?? null;
		if (!($donation instanceof Donation))
			http_error(400);

		$donation->cancel();
		unset($_SESSION['donation']);
		header('HX-Redirect: /donors/');
		exit;
	}
}

return Donate::class;
