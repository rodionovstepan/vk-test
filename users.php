<?php

	require_once('backend/ajax_handler.php');
	require_once('backend/handlers/users_handler.php');

	if ($act == 'inc_balance') {
		if (empty($context_user_id)) {
			invalid_request();
		}

		echo json_encode(inc_balance_handler());
	} else if ($act == 'login') {
		$email = trim(mysql_real_escape_string($_POST['email']));
		$pwd = $_POST['pwd'];

		echo json_encode(login_handler($email, $pwd));
	} else if ($act == 'register') {
		if (!_register_all_fields()) {
			echo json_encode(array('success' => false, 'code' => 1));
		} else {
			$username = trim(mysql_real_escape_string(htmlspecialchars($_POST['username'])));
			$email = trim(mysql_real_escape_string($_POST['email']));
			$pwd = $_POST['pwd'];
			$repwd = $_POST['repwd'];
			$role = intval($_POST['role']);

			echo json_encode(register_handler($username, $email, $pwd, $repwd, $role));
		}
	} else {
		invalid_request();
	}

	function _register_all_fields() {
		return !empty($_POST['username']) && 
			   !empty($_POST['email']) && 
			   !empty($_POST['pwd']) && 
			   !empty($_POST['repwd']) &&
			   !empty($_POST['role']);
	}

?>