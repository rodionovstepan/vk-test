<?php
	
	$is_ajax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
				strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';

	if (!$is_ajax) {
		header('Location: /');
		exit();
	}

	header('Content-Type: application/json;');
	require_once('backend/context.php');

	$act = $_POST['act'];

	if (empty($context_user_id) || empty($act) || $act != 'add_order') {
		echo json_encode(array(
			'success' => false,
			'message' => 'Invalid request')
		);

		exit();
	}

	if ($_POST['act'] == 'add_order') {
		$title = trim(mysql_real_escape_string(htmlspecialchars($_POST['title'])));
		$content = trim(mysql_real_escape_string(htmlspecialchars($_POST['content'])));
		$price = $_POST['price'];

		if (strlen($title) == 0 || strlen($content) == 0) {
			echo json_encode(array('success' => false, 'code' => 1));
			exit();
		}

		if (!preg_match('/^\d{1,15}([\,\.]\d{1,2})?$/', $price)) {
			echo json_encode(array('success' => false, 'code' => 2));
			exit();
		}

		$price = floatval($price);

		require_once('backend/db/connect.php');
		require_once('backend/db/orders_queries.php');

		db_connect();

		if (add_order($context_user_id, $context_user_name, $title, $content, $price)) {
			echo json_encode(array('success' => true));
		} else {
			echo json_encode(array('success' => false, 'code' => 3));
		}
	}

?>