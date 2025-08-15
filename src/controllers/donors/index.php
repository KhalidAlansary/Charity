<?php
require_once 'core/Router.php';

class Donors extends Handler
{
	public static function GET()
	{
		require_once 'models/users.php';
		$donors = Donor::getAll();
		require 'views/donors/index.php';
	}
}

return Donors::class;
