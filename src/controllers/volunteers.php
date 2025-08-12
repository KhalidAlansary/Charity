<?php
require_once 'models/users.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
	$volunteers = Volunteer::getAll();
	require 'views/volunteer/index.php';
}
