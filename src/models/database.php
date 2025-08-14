<?php
class Database
{
	private static $instance;
	private $dbh;

	private function __construct()
	{
		$host = getenv('POSTGRES_HOST');
		$password = trim(file_get_contents('/run/secrets/db_password'));

		$this->dbh = new PDO("pgsql:host=$host", 'postgres', $password, [
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
		]);
	}

	private function __clone() {}
	public function __wakeup()
	{
		throw new Exception('Cannot unserialize a singleton.');
	}

	public static function getInstance()
	{
		if (Database::$instance === null) {
			Database::$instance = new Database();
		}
		return Database::$instance->dbh;
	}
}

/* By: https://stackoverflow.com/a/5632171/8510495 */
function to_pg_array($set)
{
	settype($set, 'array'); // can be called with a scalar or array
	$result = array();
	foreach ($set as $t) {
		if (is_array($t)) {
			$result[] = to_pg_array($t);
		} else {
			$t = str_replace('"', '\\"', $t); // escape double quote
			if (! is_numeric($t)) // quote only non-numeric values
				$t = '"' . $t . '"';
			$result[] = $t;
		}
	}
	return '{' . implode(",", $result) . '}'; // format
}
