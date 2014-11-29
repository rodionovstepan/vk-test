<?php

	function get_customer_active_orders($customer_id) {
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

	function get_contractor_active_orders() {
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

	function add_order($customer_id, $customer_name, $title, $content, $price) {
		mysql_query("START TRANSACTION");

		$update = mysql_query(
			"UPDATE users SET order_count = order_count+1, balance = balance-" . $price . " WHERE id = " . $customer_id . ";"
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

	function cancel_order($customer_id, $order_id) {
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

?>