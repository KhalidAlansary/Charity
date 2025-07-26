<?php
$host = getenv("POSTGRES_HOST");
$password = trim(file_get_contents("/run/secrets/db_password"));

if (!$host) $host = "localhost";

try {
	$pdo = new PDO("pgsql:host=$host", "postgres", $password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
	if ($pdo) {
		echo "Connected to PostgreSQL server successfully.";
	} else {
		echo "Failed to connect to PostgreSQL server.";
	}
} catch (PDOException $e) {
	echo "Connection failed: " . $e->getMessage();
	exit;
}
