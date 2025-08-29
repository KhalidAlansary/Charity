<?php
require_once 'core/Router.php';

return new class extends Handler
{
	public function GET()
	{
		require 'views/index.php';
	}
};
