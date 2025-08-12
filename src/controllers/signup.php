<?php
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
	require 'views/signup.php';
} else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	require_once 'models/users.php';
	$name = $_POST['name'];
	$email = $_POST['email'];
	$password = $_POST['password'];
	$type = $_POST['type'];

	$user = User::signup($name, $email, $password, $type);
	if ($user === false) {
		http_response_code(403);
		readfile('components/signup_error.html');
		exit;
	}

	$_SESSION['user'] = $user;
	http_response_code(303);
	header('HX-Redirect: /profile/');
}
