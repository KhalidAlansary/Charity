<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
	if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'volunteer') {
		http_response_code(303);
		header('Location: /volunteer/login/');
		exit;
	}

	require_once 'models/models.php';
	$volunteer = Volunteer::getById($_SESSION['user_id']);
	if ($volunteer === false) {
		http_response_code(404);
		echo "Volunteer not found.";
		exit;
	}
	require 'views/volunteer/profile.php';
}
