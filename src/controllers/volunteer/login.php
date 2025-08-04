<?php
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
	$user_type = 'volunteer';
	require 'views/login.php';
} else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	require_once 'models/models.php';
	$name = $_POST['name'] ?? '';
	$email = $_POST['email'];
	$password = $_POST['password'];

	$volunteer = Volunteer::login($email, $password);

	if ($volunteer === false) {
		http_response_code(403);
		readfile('components/login_error.html');
	} else {
		session_start();
		$_SESSION['user_type'] = 'volunteer';
		$_SESSION['user_id'] = $volunteer->id;
		http_response_code(303);
		header('HX-Redirect: /volunteer/profile/');
	}
}
