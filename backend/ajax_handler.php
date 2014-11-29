<?php

	require_once('backend/context.php');

	if (!$is_ajax) {
		header('Location: /');
		exit();
	}

	header('Content-Type: application/json;');

	$act = $_POST['act'];

	function invalid_request() {
		echo json_encode(array(
			'success' => false,
			'message' => 'Invalid request')
		);

		exit();
	}

	if (empty($context_user_id) || empty($act)) {
		invalid_request();
	}

?>