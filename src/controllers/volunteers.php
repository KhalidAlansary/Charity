<?php
require_once 'core/Router.php';

class Volunteers extends Handler
{
	public static function GET()
	{
		require_once 'models/users.php';
		$volunteers = Volunteer::getAll();
		require 'views/volunteer/index.php';
	}
}

return Volunteers::class;
