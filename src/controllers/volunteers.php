<?php
require_once 'models/models.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
	$volunteers = Volunteer::getAll();
	require 'views/volunteer/index.php';
}
