<?php

	if (isset($_POST['email']) && isset($_POST['pwd'])) {
		header('Content-Type: application/json;');

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

		echo json_encode(array('success' => true, 'url' => '/'));
		exit();
	}

?>