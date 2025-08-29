<?php
require_once 'core/Router.php';
require_once 'models/payments.php';
require_once 'models/users.php';

return new class extends Handler
{
	public function __callStatic(string $method, array $params)
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

	private function PATCH(Donation $donation)
	{
		$donation->proceed();
	}

	private function DELETE(Donation $donation)
	{
		$donation->cancel();
	}
};
