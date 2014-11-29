<?php

	require_once('backend/context.php');

	if (!$is_ajax) {
		header('Location: /');
		exit();
	}

	header('Content-Type: application/json;');

	$act = $_POST['act'];

	if (empty($context_user_id) || empty($act) || $act != 'inc_balance') {
		echo json_encode(array(
			'success' => false,
			'message' => 'Invalid request')
		);
	}

	require_once('backend/db/connect.php');
	require_once('backend/db/users_queries.php');
	
	db_connect();

	if ($act == 'inc_balance') {
		$balance = inc_balance($context_user_id, 5000);
		echo json_encode(array(
			'success' => $balance > 0,
			'balance' => $balance)
		);
	}

?>