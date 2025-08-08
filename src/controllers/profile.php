<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
	require_once 'models/models.php';
	if (!isset($_SESSION['user'])) {
		header('Location: /login/');
		exit;
	}
	$user = $_SESSION['user'];
	echo "<h1>Profile of {$user->name}</h1>";
}
