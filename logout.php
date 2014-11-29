<?php

	require_once('backend/context.php');
	if (isset($context_user_id) && isset($context_user_role)) {
		unset($context_user_id);
		unset($context_user_role);
		unset($_SESSION['uid']);
		unset($_SESSION['urole']);
		session_destroy();
	}

	header('Location: /');

?>