<?php
require_once 'core/Router.php';

class AdminDonations extends Handler
{
	public static function __callStatic(string $method, array $params)
	{
		// TODO: check if user is authenticated as admin
		$id = $params['id'];
		require_once 'models/payments.php';
		require_once 'models/users.php';
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
