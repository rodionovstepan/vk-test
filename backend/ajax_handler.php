<?php

	require_once('context.php');

	if (!$is_ajax) {
		header('Location: /');
		exit();
	}

	header('Content-Type: application/json;');

	function invalid_request() {
		echo json_encode(array(
			'success' => false,
			'message' => 'Invalid request')
		);

		exit();
	}

	$act = $_POST['act'];
	$token = $_SERVER['HTTP_X_AOS_TOKEN'];

?>