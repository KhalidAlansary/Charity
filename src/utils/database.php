<?php
class Database
{
	private static $instance;
	private $dbh;

	private function __construct()
	{
		$host = getenv("POSTGRES_HOST");
		$password = trim(file_get_contents("/run/secrets/db_password"));

		$this->dbh = new PDO("pgsql:host=$host", "postgres", $password, [
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
		]);
	}

	public static function getInstance()
	{
		if (self::$instance === null) {
			self::$instance = new Database();
		}
		return self::$instance->dbh;
	}
}
