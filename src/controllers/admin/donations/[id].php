<?php
require_once 'core/Router.php';
require_once 'models/payments.php';
require_once 'models/users.php';

class AdminDonations extends Handler
{
	public static function __callStatic(string $method, array $params)
	{
		if (!isset($_SESSION['user']) || !$_SESSION['user'] instanceof Admin) {
			header('Location: /login/');
			exit;
		}
		$id = $params['id'];
		$donation = Donation::getPendingById($id);
		if (!$donation)
			http_error(404);

		self::$method($donation);
	}

	private static function PATCH(Donation $donation)
	{
		$donation->proceed();
	}

	private static function DELETE(Donation $donation)
	{
		$donation->cancel();
	}
}

return AdminDonations::class;
