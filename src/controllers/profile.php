<?php
require_once 'models/users.php';
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
	if (!isset($_SESSION['user'])) {
		header('Location: /login/');
		exit;
	}
	$user = $_SESSION['user'];
	require 'views/profile.php';
}
