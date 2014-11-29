<?php

	require_once('backend/auth.php');
	require_once('backend/def.php');

	header('Content-Type: application/json;');

	function url_by_role($role) {
		return $role == CUSTOMER_ROLE
			? 'customer.php'
			: 'contractor.php';
	}

	if (isset($context_user_id) && isset($context_user_role)) {
		echo json_encode(array(
			'success' => true, 
			'url' => url_by_role($context_user_role))
		);

		exit();
	}

	if (isset($_POST['email']) && isset($_POST['pwd'])) {
		$email = trim(mysql_real_escape_string($_POST['email']));
		$pwd = $_POST['pwd'];

		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			echo json_encode(array('success' => false, 'code' => 1));
			exit();
		}

		if (strlen($pwd) < 5) {
			echo json_encode(array('success' => false, 'code' => 2));
			exit();
		}

		require_once('backend/db/connect.php');	
		require_once('backend/db/users_queries.php');

		db_connect();

		$user = user_by_email_pwd($email, $pwd);
		if ($user == NULL) {
			echo json_encode(array('success' => false, 'code' => 3));
			exit();
		}

		login_user($user);

		echo json_encode(array(
			'success' => true, 
			'url' => url_by_role($user['role']))
		);
	}

?>