<?php
	
	require_once('backend/context.php');

	if (!$is_ajax) {
		header('Location: /');
		exit();
	}

	header('Content-Type: application/json;');

	$act = $_POST['act'];

	if (empty($context_user_id) || empty($act) || ($act != 'add_order' && $act != 'cancel_order' && $act != 'take_order')) {
		echo json_encode(array(
			'success' => false,
			'message' => 'Invalid request')
		);

		exit();
	}

	require_once('backend/db/connect.php');
	require_once('backend/db/orders_queries.php');
	require_once('backend/db/users_queries.php');

	db_connect();

	if ($act == 'add_order') {
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

		$user_info = get_user_info($context_user_id);
		if ($user_info['balance'] < $price) {
			echo json_encode(array('success' => false, 'code' => 3));
			exit();
		}

		if (add_order($context_user_id, $context_user_name, $title, $content, $price)) {
			echo json_encode(array('success' => true));
		} else {
			echo json_encode(array('success' => false, 'code' => 4));
		}
	} else if ($_POST['act'] == 'cancel_order') {
		$id = intval($_POST['oid']);

		$result = cancel_order($context_user_id, $id);

		if ($result) {
			$user_info = get_user_info($context_user_id);

			echo json_encode(array(
				'success' => true,
				'balance' => $user_info['balance'])
			);
		} else {
			echo json_encode(array('success' => false));
		}
	} else if ($_POST['act'] == 'take_order') {
		$id = intval($_POST['oid']);

		$result = take_order($context_user_id, $id);

		if ($result) {
			$user_info = get_user_info($context_user_id);

			echo json_encode(array(
				'success' => true,
				'balance' => $user_info['balance'],
				'ordercount' => $user_info['order_count'])
			);
		} else {
			echo json_encode(array('success' => false));
		}
	}

?>