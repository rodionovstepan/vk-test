<?php

	require_once('backend/db/connect.php');
	require_once('backend/db/users_queries.php');
	require_once('backend/auth.php');

	db_connect();

	function inc_balance_handler() {
		global $context_user_id;

		$balance = inc_balance_query($context_user_id, BALANCE_INC_PART);
		
		return array(
			'success' => $balance > 0,
			'balance' => $balance
		);
	}

	function login_handler($email, $pwd) {
		$validation = _login_validation($email, $pwd);
		if (!$validation['success']) {
			return $validation;
		}

		$user = get_user_by_email_pwd_query($email, $pwd);
		if ($user == NULL) {
			return array('success' => false, 'code' => 3);
		}

		login_user($user);

		return array(
			'success' => true, 
			'url' => url_by_role($user['role'])
		);
	}

	function register_handler($username, $email, $pwd, $repwd, $role) {
		$email_hash = md5($email);
		$validation = _register_validation($username, $email, $email_hash, $pwd, $repwd, $role);
		if (!$validation['success']) {
			return $validation;
		}

		$id = register_user_query($username, $email, $email_hash, $pwd, $role);

		if ($id) {
			login_user(array('id' => $id, 'role' => $role, 'username' => $username));
		} else {
			return array('success' => false, 'code' => 7);
		}

		return array(
			'success' => true, 
			'url' => $role == CUSTOMER_ROLE 
				? 'customer.php' 
				: 'contractor.php'
		);
	}

	function _register_validation($username, $email, $email_hash, $pwd, $repwd, $role) {
		$usernamelen = strlen($username);
		if ($usernamelen == 0 || $usernamelen > 30) {
			return array('success' => false, 'code' => 2);
		}

		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			return array('success' => false, 'code' => 3);
		}

		if (strlen($pwd) < 5 || $pwd != $repwd) {
			return array('success' => false, 'code' => 4);
		}

		if ($role != CUSTOMER_ROLE && $role != CONTRACTOR_ROLE) {
			return array('success' => false, 'code' => 5);
		}

		if (is_user_registered_query($email_hash)) {
			return array('success' => false, 'code' => 6);
		}

		return array('success' => true);
	}

	function _login_validation($email, $pwd) {
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			return array('success' => false, 'code' => 1);
		}

		if (strlen($pwd) < 5) {
			return array('success' => false, 'code' => 2);
		}

		return array('success' => true);
	}

?>