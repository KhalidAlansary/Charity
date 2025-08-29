<?php
require_once 'core/Router.php';

return new class extends Handler
{
	public function GET()
	{
		require_once 'models/users.php';
		$donors = Donor::getAll();
		require 'views/donors/index.php';
	}
};
