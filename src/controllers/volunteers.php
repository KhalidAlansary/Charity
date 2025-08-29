<?php
require_once 'core/Router.php';
require_once 'models/users.php';

return new class extends Handler
{
	public function GET()
	{
		$volunteers = Volunteer::getAll();
		require 'views/volunteer/index.php';
	}
};
