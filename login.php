<?php
	require_once('backend/context.php');
	
	header('Content-Type: application/json;');

	if (isset($context_user_id) && isset($context_user_role)) {
		echo json_encode(array('success' => true, 'url' => '/'));
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

		session_start();
		$_SESSION['uid'] = $user['id'];
		$_SESSION['urole'] = $user['role'];

		setcookie('sid', session_id(), time()+30*24*3600, '/', '', true, true);

		echo json_encode(array('success' => true, 'url' => '/'));
		exit();
	}

?>