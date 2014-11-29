<?php
	header('Content-Type: application/json;');

	if (!isset($_POST['username']) || !isset($_POST['email']) || 
		!isset($_POST['pwd']) || !isset($_POST['repwd']) || !isset($_POST['role'])) {
		echo json_encode(array('success' => false, 'code' => 1));
		exit();
	}

	$username = trim(mysql_real_escape_string($_POST['username']));
	$email = trim(mysql_real_escape_string($_POST['email']));
	$pwd = $_POST['pwd'];
	$repwd = $_POST['repwd'];
	$role = intval($_POST['role']);

	$usernamelen = strlen($username);
	if ($usernamelen == 0 || $usernamelen > 30) {
		echo json_encode(array('success' => false, 'code' => 2))
	}

	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		echo json_encode(array('success' => false, 'code' => 3));
	}

	if (strlen($pwd) < 5 || $pwd != $repwd) {
		echo json_encode(array('success' => false, 'code' => 4));
		exit();
	}

	if ($role > 2 || $role < 1) {
		echo json_encode(array('success' => false, 'code' => 5));
		exit();
	}

	require_once('backend/db/connect.php');	
	require_once('backend/db/users_queries.php');

	db_connect();

	if (is_user_registered($email)) {
		echo json_encode(array('success' => false, 'code' => 6));
		exit();
	}

	$id = register_user($username, $email, $pwd, $role);

	echo json_encode(array('success' => true, 'id' => $id));
?>