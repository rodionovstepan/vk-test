<?php

	require_once('context.php');

	function login_user($user) {
		if (isset($context_user_id, $context_user_role, $context_user_name)) {
			return;
		}

		$_SESSION['uid'] = $user['id'];
		$_SESSION['uname'] = $user['username'];
		$_SESSION['urole'] = $user['role'];
	}

	function logout_user() {
		unset($context_user_id);
		unset($context_user_role);
		unset($context_user_name);
		unset($_SESSION['uid']);
		unset($_SESSION['urole']);
		unset($_SESSION['uname']);
		session_destroy();
	}

?>