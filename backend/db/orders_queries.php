<?php

	function get_customer_active_orders_query($customer_id) {
		$result = mysql_query(
			"SELECT id, title, content, price, customer_id, customer_name
			 FROM orders
			 WHERE is_completed = FALSE AND is_deleted = FALSE AND customer_id = " . $customer_id . " 
			 ORDER BY id DESC;");

		if (!$result) {
			die(mysql_error());
		}

		$rows = array();
		while($row = mysql_fetch_assoc($result)) {
			$rows[] = $row;
		}

		return $rows;
	}

	function get_contractor_active_orders_query() {
		$result = mysql_query(
			"SELECT id, title, content, price, customer_id, customer_name
			 FROM orders
			 WHERE is_completed = FALSE AND is_deleted = FALSE 
			 ORDER BY id DESC;");

		if (!$result) {
			die(mysql_error());
		}

		$rows = array();
		while($row = mysql_fetch_assoc($result)) {
			$rows[] = $row;
		}

		return $rows;
	}

	function add_order_query($customer_id, $customer_name, $title, $content, $price) {
		mysql_query("START TRANSACTION");

		$update = mysql_query(
			"UPDATE users SET order_count = order_count+1, balance = balance-" . number_format($price, 2, '.', '') . " WHERE id = " . $customer_id . ";"
		);

		$insert = mysql_query(
			"INSERT INTO orders (title, content, price, customer_id, customer_name, is_completed, is_deleted)
			 VALUES ('" . $title . "', '" . $content . "', " . $price . ", " . $customer_id . ", '" . $customer_name . "', FALSE, FALSE);"
		);

		$id = mysql_insert_id();

		if (!$update || !$insert) {
			mysql_query("ROLLBACK");
			return 0;
		} 

		mysql_query("COMMIT");
		return $id;
	}

	function cancel_order_query($customer_id, $order_id) {
		mysql_query("START TRANSACTION");

		$dec = mysql_query(
			"UPDATE users 
			 SET order_count = order_count-1,
			     balance = balance + (SELECT price FROM orders WHERE id = " . $order_id . ")
			 WHERE id = " . $customer_id . ";"
		);

		$cancel = mysql_query(
			"UPDATE orders SET is_deleted = TRUE WHERE customer_id = " . $customer_id . " AND id = " . $order_id . " AND is_completed = FALSE;"
		);

		if (!$dec || !$cancel || !mysql_affected_rows()) {
			mysql_query("ROLLBACK");
			return 0;
		}

		mysql_query("COMMIT");
		return 1;
	}

	function take_order_query($contractor_id, $order_id) {
		mysql_query("START TRANSACTION");

		$price = _get_order_price($order_id);
		if ($price == 0) {
			mysql_query("ROLLBACK");
			return 0;
		}

		$aos_portion = $price * SYSTEM_PERCENT/100.0;
		$user_portion = $price - $aos_portion;

		$inc = mysql_query("UPDATE users 
							SET order_count = order_count+1,
								balance = balance + " . $user_portion . "
							WHERE id = " . $contractor_id . ";");

		$take = mysql_query("UPDATE orders 
							 SET is_completed = TRUE 
							 WHERE id = " . $order_id . " AND 
							 	   is_completed = FALSE AND
							 	   is_deleted = FALSE;");

		if (!$inc || !$take || !mysql_affected_rows()) {
			mysql_query("ROLLBACK");
			return 0;
		}

		mysql_query("COMMIT");
		return 1;
	}

	function _get_order_price($order_id) {
		$result = mysql_query("SELECT price FROM orders WHERE id = " . $order_id . ";");

		if (!$result || mysql_num_rows($result) == 0) {
			return 0;
		}

		$row = mysql_fetch_array($result);
		return $row[0];
	}

?>