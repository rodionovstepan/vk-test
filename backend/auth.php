<?php

	require_once('context.php');

	function login_user($user) {
		$_SESSION['uid'] = $user['id'];
		$_SESSION['uname'] = $user['username'];
		$_SESSION['urole'] = $user['role'];
	}

	function logout_user() {
		if (!empty($_SESSION['uid']) && !empty($_SESSION['urole']) && !empty($_SESSION['uname'])) {
			unset($context_user_id);
			unset($context_user_role);
			unset($context_user_name);

			unset($_SESSION['uid']);
			unset($_SESSION['urole']);
			unset($_SESSION['uname']);
			
			session_destroy();
		}
	}

?>