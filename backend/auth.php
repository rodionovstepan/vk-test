<?php

	require_once('backend/context.php');

	function loginUser($user) {
		if (isset($context_user_id) && isset($context_user_role)) {
			return;
		}

		$_SESSION['uid'] = $user['id'];
		$_SESSION['urole'] = $user['role'];
	}

	function logoutUser() {
		if (!isset($context_user_id) || !isset($context_user_role)) {
			return;
		}

		unset($context_user_id);
		unset($context_user_role);
		unset($_SESSION['uid']);
		unset($_SESSION['urole']);
		session_destroy();
	}

?>