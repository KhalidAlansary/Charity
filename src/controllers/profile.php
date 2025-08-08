<?php
require_once 'models/models.php';
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
	if (!isset($_SESSION['user'])) {
		header('Location: /login/');
		exit;
	}
	$user = $_SESSION['user'];
	require 'views/profile.php';
}
