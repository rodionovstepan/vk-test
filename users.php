<?php

	require_once('backend/ajax_handler.php');
	require_once('backend/handlers/users_handler.php');

	if ($act == 'inc_balance') {
		if (empty($context_user_id) || empty($context_user_token) || $context_user_token != $token) {
			invalid_request();
		}

		echo json_encode(inc_balance_handler());
	} else if ($act == 'login') {
		$email = $_POST['email'];
		$pwd = $_POST['pwd'];

		echo json_encode(login_handler($email, $pwd));
	} else if ($act == 'register') {
		if (!_register_all_fields()) {
			echo json_encode(array('success' => false, 'code' => 1));
		} else {
			$username = trim(htmlspecialchars($_POST['username']));
			$email = trim($_POST['email']);
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