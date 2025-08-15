<?php
require_once 'core/Router.php';

class Index extends Handler
{
	public static function GET()
	{
		require 'views/index.php';
	}
}

return Index::class;
