<?php
require_once 'core/Router.php';
require_once 'models/events.php';

return new class extends Handler
{
	public function GET()
	{
		if (!isset($_SESSION['user']) || !$_SESSION['user'] instanceof Admin) {
			header('Location: /login/');
			exit;
		}
		$fundraisers = Fundraiser::getAll();
		require 'views/admin/fundraisers.php';
	}

	public function POST()
	{
		if (!isset($_SESSION['user']) || !$_SESSION['user'] instanceof Admin) {
			header('Location: /login/');
			exit;
		}
		$fundraiser = new Fundraiser($_POST['title'], $_POST['date']);
		$fundraiser->save();
	}
};
