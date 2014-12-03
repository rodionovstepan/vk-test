<?php

	function get_customer_active_orders_query($customer_id) {
		global $orders_db_link;

		return select_query(
			"SELECT id, title, content, price, customer_id, customer_name
			 FROM orders
			 WHERE is_completed = FALSE AND is_deleted = FALSE AND customer_id = ? 
			 ORDER BY id DESC",
			array('i', $customer_id), $orders_db_link
		);
	}

	function get_contractor_active_orders_query() {
		global $orders_db_link;

		return select_query(
			"SELECT id, title, content, price, customer_id, customer_name
			 FROM orders
			 WHERE is_completed = FALSE AND is_deleted = FALSE 
			 ORDER BY id DESC", NULL, $orders_db_link
		);
	}

	function add_order_query($customer_id, $customer_name, $title, $content, $price) {
		global $orders_db_link, $users_db_link, $events_db_link;

		start_transaction();

		$update = update_query(
			"UPDATE users 
			 SET order_count = order_count + 1, 
				 balance = balance - ?
			 WHERE id = ?",
			array('di', $price, $customer_id), $users_db_link
		);

		$order_id = insert_query(
			"INSERT INTO orders (title, content, price, customer_id, customer_name, is_completed, is_deleted)
			 VALUES (?, ?, ?, ?, ?, FALSE, FALSE)",
			array('ssdis', $title, $content, $price, $customer_id, $customer_name), $orders_db_link
		);

		$event = insert_query(
			"INSERT INTO events (order_id, type, profit) VALUES (?, 1, 0)",
			array('i', $order_id), $events_db_link
		);

		if (!$update || $order_id <= 0 || !$event) {
			rollback_transaction();
			return 0;
		}

		commit_transaction();
		return $order_id;
	}

	function cancel_order_query($customer_id, $order_id) {
		global $orders_db_link, $users_db_link, $events_db_link;

		start_transaction();

		$price = _get_order_price($order_id, $orders_db_link);
		$cancel_balance = update_query(
			"UPDATE users 
			 SET order_count = order_count - 1,
				  balance = balance + ?
			 WHERE id = ?",
			array('di', $price, $customer_id), $users_db_link
		);

		$cancel_order = update_query(
			"UPDATE orders 
			 SET is_deleted = TRUE 
			 WHERE customer_id = ? AND 
					 id = ? AND 
					 is_completed = FALSE AND 
					 is_deleted = FALSE",
			array('ii', $customer_id, $order_id), $orders_db_link
		);

		$event = insert_query(
			"INSERT INTO events (order_id, type, profit) VALUES (?, 2, 0)",
			array('i', $order_id), $events_db_link
		);

		if (!$cancel_balance || !$cancel_order || !$event) {
			rollback_transaction();
			return 0;
		}

		commit_transaction();
		return 1;
	}

	function take_order_query($contractor_id, $order_id) {
		global $orders_db_link, $users_db_link, $events_db_link;

		start_transaction();

		$price = _get_order_price($order_id, $orders_db_link);
		if ($price < 0) {
			rollback_transaction();
			return 0;
		}

		$user_balance = _get_user_balance($contractor_id, $users_db_link);
		if ($user_balance < 0) {
			rollback_transaction();
			return 0;
		}

		$aos_portion = aos_money_mul($price, SYSTEM_PERCENT/100.0);
		$user_portion = aos_money_sub($price, $aos_portion);

		$new_user_balance = aos_money_add($user_balance, $user_portion);
		if (aos_money_compare(MAX_USER_BALANCE, $new_user_balance) < 0) {
			rollback_transaction();
			return -1;
		}

		$update_balance = update_query(
			"UPDATE users 
			 SET order_count = order_count + 1,
				 balance = balance + ?
			 WHERE id = ?",
			array('di', $user_portion, $contractor_id), $users_db_link
		);

		$update_order = update_query(
			"UPDATE orders 
			 SET is_completed = TRUE 
			 WHERE id = ? AND 
					 is_completed = FALSE AND
					 is_deleted = FALSE",
			array('i', $order_id), $orders_db_link
		);

		$event = insert_query(
			"INSERT INTO events (order_id, type, profit) VALUES (?, 3, ?)",
			array('id', $order_id, $aos_portion), $events_db_link
		);

		if (!$update_balance || !$update_order || !$event) {
			rollback_transaction();
			return 0;
		}

		commit_transaction();
		return 1;
	}

	function _get_order_price($order_id, $link) {
		$select = select_query(
			"SELECT price FROM orders WHERE id = ?",
			array('i', $order_id), $link
		);

		if (empty($select)) {
			return -1;
		}

		return $select[0]['price'];
	}

	function _get_user_balance($user_id, $link) {
		$select = select_query(
			"SELECT balance FROM users WHERE id = ?",
			array('i', $user_id), $link
		);

		if (empty($select)) {
			return -1;
		}

		return $select[0]['balance'];
	}

?>