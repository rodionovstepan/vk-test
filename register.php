<?php
	header('Content-Type: application/json;');

	if (!isset($_POST['username']) || !isset($_POST['email']) || 
		!isset($_POST['pwd']) || !isset($_POST['repwd']) || !isset($_POST['role'])) {
		echo json_encode(array('success' => false, 'code' => 1));
		exit();
	}

	$username = trim(mysql_escape_string($_POST['username']));
	$email = trim(mysql_escape_string($_POST['email']));
	$pwd = mysql_escape_string($_POST['pwd']);
	$repwd = mysql_escape_string($_POST['repwd']);
	$role = intval($_POST['role']);

	if ($pwd != $repwd) {
		echo json_encode(array('success' => false, 'code' => 2));
		exit();
	}

	if ($role > 2 || $role < 1) {
		echo json_encode(array('success' => false, 'code' => 3));
		exit();
	}

	echo json_encode(array('success' => true, 'code' => 0));
?>