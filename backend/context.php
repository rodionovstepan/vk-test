<?php

	require_once('def.php');

	function url_by_role($role) {
		return $role == CUSTOMER_ROLE
			? 'customer.php'
			: 'contractor.php';
	}

	session_start();

	if (!empty($_SESSION['uid']) && !empty($_SESSION['urole']) && !empty($_SESSION['uname'])) {
		$context_user_id = intval($_SESSION['uid']);
		$context_user_role = intval($_SESSION['urole']);
		$context_user_name = $_SESSION['uname'];
	}

	$is_ajax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
				strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';

?>