<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	require_once 'models/models.php';

	$volunteer = new Volunteer();
	$volunteer->name = $_POST['name'];
	$volunteer->email = $_POST['email'];
	$volunteer->skills = $_POST['skills'];
	$volunteer->availability = $_POST['availability'];

	$volunteer->create();
	echo "Volunteer created successfully.";
}
