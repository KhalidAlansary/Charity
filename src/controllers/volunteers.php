<?php
require_once 'core/Router.php';

return new class extends Handler
{
	public function GET()
	{
		require_once 'models/users.php';
		$volunteers = Volunteer::getAll();
		require 'views/volunteer/index.php';
	}
};
