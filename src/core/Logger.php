<?php
class Logger
{
	private static self $instance;
	const LOG_FILE = '/var/www/logs/log.txt';
	private $fh;

	private function __construct()
	{
		$this->fh = fopen(self::LOG_FILE, 'a');
	}

	private function __clone() {}
	public function __wakeup()
	{
		throw new Exception('Cannot unserialize a singleton.');
	}

	public static function getInstance(): self
	{
		if (!isset(self::$instance)) {
			self::$instance = new self;
		}
		return self::$instance;
	}

	public function write(string $message): void
	{
		$timestamp = date('Y-m-d H:i:s');
		$logMessage = "[$timestamp] $message\n";
		fwrite($this->fh, $logMessage);
	}
}
