<?php

	require_once('context.php');

	function login_user($user) {
		$_SESSION['uid'] = $user['id'];
		$_SESSION['uname'] = $user['username'];
		$_SESSION['urole'] = $user['role'];
		$_SESSION['utoken'] = _generate_token();

		session_regenerate_id(true);
	}

	function logout_user() {
		if (!empty($_SESSION['uid']) && !empty($_SESSION['urole']) && !empty($_SESSION['uname']) && !empty($_SESSION['utoken'])) {
			unset($context_user_id);
			unset($context_user_role);
			unset($context_user_name);
			unset($context_user_token);

			unset($_SESSION['uid']);
			unset($_SESSION['urole']);
			unset($_SESSION['uname']);
			unset($_SESSION['utoken']);

			session_regenerate_id(true);
			session_destroy();
		}
	}

	function _generate_token() {
		$alpha = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$length = strlen($alpha);
		$token = '';
		
		for ($i = 0; $i < 10; $i++) {
		  $token .= $alpha[rand(0, $length - 1)];
		}

		return md5(md5($token));
	}

?>