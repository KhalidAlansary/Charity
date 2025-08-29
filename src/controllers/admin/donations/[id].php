<?php
require_once 'core/ProtectedHandler.php';
require_once 'core/Router.php';
require_once 'models/payments.php';
require_once 'models/users.php';

$handler = new class extends Handler
{
	public function __callStatic(string $method, array $params)
	{
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

return new ProtectedHandler($handler, Admin::class);
