<?php
require_once 'core/ProtectedHandler.php';
require_once 'core/Router.php';
require_once 'models/events.php';
require_once 'models/users.php';

$handler = new class extends Handler
{
	public function GET()
	{
		$fundraisers = Fundraiser::getAll();
		require 'views/admin/fundraisers.php';
	}

	public function POST()
	{
		$fundraiser = new Fundraiser($_POST['title'], $_POST['date']);
		$fundraiser->save();
	}
};

return new ProtectedHandler($handler, Admin::class);
