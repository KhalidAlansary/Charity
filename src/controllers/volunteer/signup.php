<?php
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
	$user_type = 'volunteer';
	require 'views/signup.php';
} else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	require_once 'models/models.php';
	$name = $_POST['name'] ?? '';
	$email = $_POST['email'];
	$password = $_POST['password'];

	$volunteer = Volunteer::signup($email, $name, $password);
	if ($volunteer === false) {
		http_response_code(403);
		require 'components/signup_error.php';
		exit;
	}

	session_start();
	$_SESSION['user_type'] = 'volunteer';
	$_SESSION['user_id'] = $volunteer->id;
	http_response_code(303);
	header('HX-Redirect: /volunteer/profile/');
}
