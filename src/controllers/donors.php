<?php
require_once 'models/users.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
	$donors = Donor::getAll();
	require 'views/donors/index.php';
}
