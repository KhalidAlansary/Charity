<?php

abstract class Singleton
{
	private static $instances = [];

	protected function __construct() {}
	protected function __clone() {}
	public function __wakeup()
	{
		throw new Exception('Cannot unserialize a singleton.');
	}

	public static function getInstance(): static
	{
		$cls = static::class;
		if (!isset(self::$instances[$cls])) {
			self::$instances[$cls] = new static;
		}

		return self::$instances[$cls];
	}
}

class Logger extends Singleton
{
	const LOG_FILE = '/var/www/logs/log.txt';
	private $fh;

	protected function __construct()
	{
		$this->fh = fopen(self::LOG_FILE, 'a');
	}

	public function write(string $message): void
	{
		$timestamp = date('Y-m-d H:i:s');
		$logMessage = "[$timestamp] $message\n";
		fwrite($this->fh, $logMessage);
	}

	public static function log(string $message): void
	{
		$instance = self::getInstance();
		$instance->write($message);
	}
}

class Database extends Singleton
{
	private $dbh;

	protected function __construct()
	{
		$host = getenv('POSTGRES_HOST');
		$password = trim(file_get_contents('/run/secrets/db_password'));

		$this->dbh = new PDO("pgsql:host=$host", 'postgres', $password, [
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
		]);
	}

	public static function getHandle(): PDO
	{
		return self::getInstance()->dbh;
	}

	/* By: https://stackoverflow.com/a/5632171/8510495 */
	public static function to_pg_array($set)
	{
		settype($set, 'array'); // can be called with a scalar or array
		$result = array();
		foreach ($set as $t) {
			if (is_array($t)) {
				$result[] = self::to_pg_array($t);
			} else {
				$t = str_replace('"', '\\"', $t); // escape double quote
				if (! is_numeric($t)) // quote only non-numeric values
					$t = '"' . $t . '"';
				$result[] = $t;
			}
		}
		return '{' . implode(",", $result) . '}'; // format
	}
}
