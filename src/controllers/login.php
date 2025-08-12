<?php
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
	require 'views/login.php';
} else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	require_once 'models/users.php';
	$email = $_POST['email'];
	$password = $_POST['password'];

	$user = User::login($email, $password);

	if ($user === false) {
		http_response_code(403);
		readfile('components/login_error.html');
	} else {
		$_SESSION['user'] = $user;
		http_response_code(303);
		header('HX-Redirect: /profile/');
	}
}
