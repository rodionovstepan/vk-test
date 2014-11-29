<?php
	require_once('backend/context.php');

	if (!$is_ajax) {
		header('Location: /');
		exit();
	}

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
		echo json_encode(array('success' => false, 'code' => 2));
		exit();
	}

	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		echo json_encode(array('success' => false, 'code' => 3));
		exit();
	}

	if (strlen($pwd) < 5 || $pwd != $repwd) {
		echo json_encode(array('success' => false, 'code' => 4));
		exit();
	}

	if ($role != CUSTOMER_ROLE && $role != CONTRACTOR_ROLE) {
		echo json_encode(array('success' => false, 'code' => 5));
		exit();
	}

	require_once('backend/db/connect.php');	
	require_once('backend/db/users_queries.php');
	require_once('backend/auth.php');

	db_connect();

	if (is_user_registered($email)) {
		echo json_encode(array('success' => false, 'code' => 6));
		exit();
	}

	$id = register_user($username, $email, $pwd, $role);

	login_user(array('id' => $id, 'role' => $role));

	echo json_encode(array(
		'success' => true, 
		'url' => $role == CUSTOMER_ROLE 
			? 'customer.php' 
			: 'contractor.php')
	);
?>