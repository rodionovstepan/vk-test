<?php
	
	require_once('backend/db/connect.php');
	require_once('backend/db/orders_queries.php');
	require_once('backend/db/users_queries.php');
	require_once('backend/money.php');

	db_connect();

	function add_order_handler($title, $content, $price) {
		global $context_user_id, $context_user_name;

		$validation = _add_order_validation($title, $content, $price);
		if (!$validation['success']) {
			return $validation;
		}

		if (add_order_query($context_user_id, $context_user_name, $title, $content, aos_money($price))) {
			return array('success' => true);
		} else {
			return array('success' => false, 'code' => 4);
		}
	}

	function cancel_order_handler($order_id) {
		global $context_user_id;

		$result = cancel_order_query($context_user_id, $order_id);

		if ($result) {
			$user_info = get_user_info_query($context_user_id);

			return array(
				'success' => true,
				'balance' => $user_info['balance']
			);
		} else {
			return array('success' => false);
		}
	}

	function take_order_handler($order_id) {
		global $context_user_id;

		$result = take_order_query($context_user_id, $order_id);

		if ($result) {
			$user_info = get_user_info_query($context_user_id);

			return array(
				'success' => true,
				'balance' => $user_info['balance'],
				'ordercount' => $user_info['order_count']
			);
		} else {
			return array('success' => false);
		}
	}

	function _add_order_validation($title, $content, $price) {
		global $context_user_id;
		
		if (strlen($title) == 0 || strlen($content) == 0) {
			return array('success' => false, 'code' => 1);
		}

		if (!preg_match('/^\d{1,15}([\,\.]\d{1,2})?$/', $price) || floatval($price) <= 0) {
			return array('success' => false, 'code' => 2);
		}

		$user_info = get_user_info_query($context_user_id);
		if (aos_money_compare($user_info['balance'], $price) < 0) {
			return array('success' => false, 'code' => 3);
		}

		return array('success' => true);
	}
?>