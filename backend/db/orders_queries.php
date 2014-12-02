<?php

	function get_customer_active_orders_query($customer_id) {
		global $orders_db_link;

		$result = mysqli_query($orders_db_link,
			"SELECT id, title, content, price, customer_id, customer_name
			 FROM orders
			 WHERE is_completed = FALSE AND is_deleted = FALSE AND customer_id = $customer_id 
			 ORDER BY id DESC;"
		);

		if (!$result) {
			return array();
		}

		$rows = array();
		while($row = mysqli_fetch_assoc($result)) {
			$rows[] = $row;
		}

		return $rows;
	}

	function get_contractor_active_orders_query() {
		global $orders_db_link;

		$result = mysqli_query($orders_db_link, 
			"SELECT id, title, content, price, customer_id, customer_name
			 FROM orders
			 WHERE is_completed = FALSE AND is_deleted = FALSE 
			 ORDER BY id DESC;"
		);

		if (!$result) {
			return array();
		}

		$rows = array();
		while($row = mysqli_fetch_assoc($result)) {
			$rows[] = $row;
		}

		return $rows;
	}

	function add_order_query($customer_id, $customer_name, $title, $content, $price) {
		global $orders_db_link, $users_db_link, $events_db_link;

		start_transaction();

		$update = mysqli_query($users_db_link,
			"UPDATE users 
			 SET order_count = order_count+1, 
			 	 balance = balance-$price 
			 WHERE id = $customer_id;"
		);

		$insert = mysqli_query($orders_db_link,
			"INSERT INTO orders (title, content, price, customer_id, customer_name, is_completed, is_deleted)
			 VALUES ('$title', '$content', $price, $customer_id, '$customer_name', FALSE, FALSE);"
		);

		$id = mysqli_insert_id($orders_db_link);

		$event = mysqli_query($events_db_link,
			"INSERT INTO events (order_id, type, profit) VALUES ($id, 1, 0);"
		);

		if (!$update || !$insert || !$event) {
			rollback_transaction();
			return 0;
		}

		commit_transaction();
		
		return $id;
	}

	function cancel_order_query($customer_id, $order_id) {
		global $orders_db_link, $users_db_link, $events_db_link;

		start_transaction();

		$price = _get_order_price($order_id, $orders_db_link);
		$dec = mysqli_query($users_db_link,
			"UPDATE users 
			 SET order_count = order_count-1,
			     balance = balance + $price
			 WHERE id = $customer_id;"
		);

		$cancel = mysqli_query($orders_db_link,
			"UPDATE orders 
			 SET is_deleted = TRUE 
			 WHERE customer_id = $customer_id AND 
			 	   id = $order_id AND 
			 	   is_completed = FALSE AND 
			 	   is_deleted = FALSE;"
		);

		$canceled = mysqli_affected_rows($orders_db_link);
		$event = mysqli_query($events_db_link,
			"INSERT INTO events (order_id, type, profit) VALUES ($order_id, 2, 0);"
		);

		if (!$dec || !$cancel || !$canceled || !$event) {
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

		$inc = mysqli_query($users_db_link,
			"UPDATE users 
			 SET order_count = order_count+1,
				 balance = balance + $user_portion
			 WHERE id = $contractor_id;"
		);

		$take = mysqli_query($orders_db_link,
			"UPDATE orders 
			 SET is_completed = TRUE 
			 WHERE id = $order_id AND 
				   is_completed = FALSE AND
				   is_deleted = FALSE;"
		);

		$taken = mysqli_affected_rows($orders_db_link);
		$event = mysqli_query($events_db_link,
			"INSERT INTO events (order_id, type, profit) VALUES ($order_id, 3, $aos_portion);"
		);

		if (!$inc || !$take || !$taken || !$event) {
			rollback_transaction();
			return 0;
		}

		commit_transaction();
		return 1;
	}

	function _get_order_price($order_id, $link) {
		$result = mysqli_query($link,
			"SELECT price FROM orders WHERE id = $order_id;"
		);

		if (!$result || !mysqli_num_rows($result)) {
			return -1;
		}

		$row = mysqli_fetch_array($result);
		return $row[0];
	}

	function _get_user_balance($user_id, $link) {
		$result = mysqli_query($link,
			"SELECT balance FROM users WHERE id = $user_id;"
		);

		if (!$result || !mysqli_num_rows($result)) {
			return -1;
		}

		$row = mysqli_fetch_array($result);
		return $row[0];
	}

?>